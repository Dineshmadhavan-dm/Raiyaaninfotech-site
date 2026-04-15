<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ResignationController extends Controller
{

    public function __construct()
{
    // Restrict all Kanban board routes to only Super Admin (categorie 1) and Admin (categorie 3)
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied .');
        }

        return $next($request);
    });


}


    // public  function __construct()
    // {
    //     $this->middleware('permission:hr->resignation view')->only(['resignationlist', 'show']);
    //     $this->middleware('permission:hr->resignation create')->only(['resignationcreate', 'resignationstore']);
    //     $this->middleware('permission:hr->resignation edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->resignation delete')->only(['destroy']);
    // }


    public function resignationlist()
    {
        $resignations = Resignation::with([
            'employee',
            'departmentRelation',
            'designationRelation'
        ])
            ->where('delete_status', 1)
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.resignation.list', compact('resignations', 'employees', 'departments'));
    }

    public function resignationcreate()
    {
        return view('dashboard.hr.resignation.create');
    }

    public function resignationstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'is_voluntary' => 'required|boolean',
            'has_notice_period' => 'required|boolean',
            'notice_start_date' => 'nullable|date|required_if:has_notice_period,1',
            'notice_end_date' => 'nullable|date|required_if:has_notice_period,1',
            'notice_period_duration' => 'nullable|string|required_if:has_notice_period,1',
            'last_working_day' => 'nullable|date|required_if:has_notice_period,0',
            'resignation_reason' => 'required|integer|between:0,7',
            'reason_other_details' => 'nullable|string|required_if:resignation_reason,0',
            'reason_details' => 'nullable|string',
            'resignation_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'can_be_rehired' => 'required|boolean',
            'rehire_conditions' => 'nullable|string|required_if:can_be_rehired,1',
            'date_of_resignation' => 'required|date',
            'employee_signature' => 'nullable|string',
            'management_signature' => 'required|string',
            'full_name' => 'required|string',
            'date_of_hire' => 'required|date',
            'acknowledgement' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file upload
            $resignationDocumentPath = null;
            if ($request->hasFile('resignation_document')) {
                $file = $request->file('resignation_document');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/resignation');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $resignationDocumentPath = 'employeedocuments/resignation/' . $fileName;
            }

            // Handle signatures
            $employeeSignaturePath = $this->processSignature($request->employee_signature, 'employee_');
            $managementSignaturePath = $this->processSignature($request->management_signature, 'management_');

            // Create resignation record
            Resignation::create([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_id,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'is_voluntary' => $request->is_voluntary,
                'has_notice_period' => $request->has_notice_period,
                'notice_start_date' => $request->notice_start_date,
                'notice_end_date' => $request->notice_end_date,
                'notice_period' => $request->notice_period_duration,
                'last_working_day' => $request->last_working_day,
                'resignation_reason' => $request->resignation_reason,
                'reason_other_details' => $request->reason_other_details,
                'reason_details' => $request->reason_details,
                'resignation_document' => $resignationDocumentPath,
                'can_be_rehired' => $request->can_be_rehired,
                'rehire_conditions' => $request->rehire_conditions,
                'date_of_resignation' => $request->date_of_resignation,
                'employee_signature' => $employeeSignaturePath,
                'management_signature' => $managementSignaturePath,
                'full_name' => $request->employee_id,
                'acknowledgement' => $request->acknowledgement,
                'date_of_hire' => $request->date_of_hire,
                'delete_status' => 1,
            ]);

            return redirect()->route('resignationlist')
                ->with('success', 'Resignation record created successfully!');
        } catch (\Exception $e) {
            \Log::error('Resignation creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating resignation record: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processSignature($signatureData, $prefix)
    {
        if (!$signatureData || !str_contains($signatureData, 'data:image')) {
            return null;
        }

        try {
            // Extract image data and type
            if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
                $data = substr($signatureData, strpos($signatureData, ',') + 1);
                $type = strtolower($type[1]);
            } else {
                $data = $signatureData;
                $type = 'png';
            }

            $decoded = base64_decode($data);
            if ($decoded === false) {
                return null;
            }

            $currentDateTime = Carbon::now()->format('dmYHis');
            $fileName = $prefix . 'signature_' . $currentDateTime . '.' . $type;
            $destinationPath = public_path('employeesignatures/resignation');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            file_put_contents($destinationPath . '/' . $fileName, $decoded);
            return 'employeesignatures/resignation/' . $fileName;
        } catch (\Exception $e) {
            \Log::error('Signature processing error: ' . $e->getMessage());
            return null;
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('term');

        $employees = Employee::with('Departmentid', 'Designationid')
            ->where('fullname', 'LIKE', "%{$searchTerm}%")
            ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%")
            ->select(
                'emp_id',
                'fullname',
                'employee_id',
                'email_company as email',
                'cur_department',
                'cur_designation',
                'image',
                'dojprovision_from_date'
            )
            ->limit(10)
            ->get()
            ->map(function ($employee) {
                $employee->department = $employee->Departmentid ? [
                    'dep_id' => $employee->Departmentid->dep_id,
                    'dep_name' => $employee->Departmentid->dep_name
                ] : null;
                $employee->designation = $employee->Designationid ? [
                    'des_id' => $employee->Designationid->des_id,
                    'des_name' => $employee->Designationid->des_name
                ] : null;

                return $employee;
            });

        return response()->json($employees);
    }

    public function show($id)
    {
        try {
            $resignation = Resignation::with([
                'employee',
                'departmentRelation',
                'designationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.resignation.show', compact('resignation'));
        } catch (\Exception $e) {
            \Log::error('Error fetching resignation details: ' . $e->getMessage());
            return redirect()->route('resignationlist')
                ->with('error', 'Resignation record not found or unable to load details.');
        }
    }

    public function edit($id)
    {
        try {
            $resignation = Resignation::with([
                'employee',
                'departmentRelation',
                'designationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.resignation.edit', compact('resignation'));
        } catch (\Exception $e) {
            \Log::error('Error fetching resignation for edit: ' . $e->getMessage());
            return redirect()->route('resignationlist')
                ->with('error', 'Resignation record not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'is_voluntary' => 'required|boolean',
            'has_notice_period' => 'required|boolean',
            'notice_start_date' => 'nullable|date|required_if:has_notice_period,1',
            'notice_end_date' => 'nullable|date|required_if:has_notice_period,1',
            'notice_period_duration' => 'nullable|string|required_if:has_notice_period,1',
            'last_working_day' => 'nullable|date|required_if:has_notice_period,0',
            'resignation_reason' => 'required|integer|between:0,7',
            'reason_other_details' => 'nullable|string|required_if:resignation_reason,0',
            'reason_details' => 'nullable|string',
            'resignation_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'can_be_rehired' => 'required|boolean',
            'rehire_conditions' => 'nullable|string|required_if:can_be_rehired,1',
            'date_of_resignation' => 'required|date',
            'employee_signature_data' => 'nullable|string',
            'employee_signature_delete' => 'nullable|in:0,1',
            'management_signature_data' => 'nullable|string',
            'management_signature_delete' => 'nullable|in:0,1',
            'full_name' => 'required|string',
            'date_of_hire' => 'required|date',
            'acknowledgement' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $resignation = Resignation::findOrFail($id);

            // Handle file upload
            $resignationDocumentPath = $resignation->resignation_document;
            if ($request->hasFile('resignation_document')) {
                // Delete old file if exists
                if ($resignationDocumentPath && file_exists(public_path($resignationDocumentPath))) {
                    unlink(public_path($resignationDocumentPath));
                }

                $file = $request->file('resignation_document');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/resignation');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $resignationDocumentPath = 'employeedocuments/resignation/' . $fileName;
            }

            // Handle employee signature
            $employeeSignaturePath = $resignation->employee_signature;
            if ($request->input('employee_signature_delete') == '1') {
                if ($employeeSignaturePath && file_exists(public_path($employeeSignaturePath))) {
                    unlink(public_path($employeeSignaturePath));
                }
                $employeeSignaturePath = null;
            }

            if ($request->filled('employee_signature_data')) {
                // Delete old signature if exists
                if ($employeeSignaturePath && file_exists(public_path($employeeSignaturePath))) {
                    unlink(public_path($employeeSignaturePath));
                }

                $employeeSignaturePath = $this->processSignature($request->employee_signature_data, 'employee_');
            }

            // Handle management signature
            $managementSignaturePath = $resignation->management_signature;
            if ($request->input('management_signature_delete') == '1') {
                if ($managementSignaturePath && file_exists(public_path($managementSignaturePath))) {
                    unlink(public_path($managementSignaturePath));
                }
                $managementSignaturePath = null;
            }

            if ($request->filled('management_signature_data')) {
                // Delete old signature if exists
                if ($managementSignaturePath && file_exists(public_path($managementSignaturePath))) {
                    unlink(public_path($managementSignaturePath));
                }

                $managementSignaturePath = $this->processSignature($request->management_signature_data, 'management_');
            }

            // Update resignation record
            $resignation->update([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_id,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'is_voluntary' => $request->is_voluntary,
                'has_notice_period' => $request->has_notice_period,
                'notice_start_date' => $request->notice_start_date,
                'notice_end_date' => $request->notice_end_date,
                'notice_period' => $request->notice_period_duration,
                'last_working_day' => $request->last_working_day,
                'resignation_reason' => $request->resignation_reason,
                'reason_other_details' => $request->reason_other_details,
                'reason_details' => $request->reason_details,
                'resignation_document' => $resignationDocumentPath,
                'can_be_rehired' => $request->can_be_rehired,
                'rehire_conditions' => $request->rehire_conditions,
                'date_of_resignation' => $request->date_of_resignation,
                'employee_signature' => $employeeSignaturePath,
                'management_signature' => $managementSignaturePath,
                'full_name' => $request->employee_id,
                'acknowledgement' => $request->acknowledgement,
                'date_of_hire' => $request->date_of_hire,
            ]);

            return redirect()->route('resignationlist')
                ->with('success', 'Resignation record updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Resignation update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating resignation record: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $resignation = Resignation::findOrFail($id);
            $resignation->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resignation record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Resignation deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting resignation record'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Handover;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HandoverController extends Controller
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
    //     $this->middleware('permission:hr->handover view')->only(['handoverlist', 'show']);
    //     $this->middleware('permission:hr->handover create')->only(['handovercreate', 'handoverstore']);
    //     $this->middleware('permission:hr->handover edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->handover delete')->only(['destroy']);
    // }


    public function handoverlist()
    {
        $handovers = Handover::with([
            'handoverEmployee',
            'takeoverEmployee',
            'handoverDepartmentRelation',
            'handoverDesignationRelation',
            'takeoverDepartmentRelation',
            'takeoverDesignationRelation'
        ])
            ->where('delete_status', 1)
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.handover.list', compact('handovers', 'employees', 'departments'));
    }

    public function handovercreate()
    {
        return view('dashboard.hr.handover.create');
    }

    public function handoverstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handover_employee_id' => 'required|exists:employees,emp_id',
            'takeover_employee_id' => 'required|exists:employees,emp_id',
            'reason' => 'required|integer|in:1,2,3,4,0',
            'reason_other' => 'nullable|required_if:reason,0|string|max:255',

            'handover_date' => 'required|date',

            // Task validation
            'tasks.*.task_no' => 'nullable|string|max:255',
            'tasks.*.task_name' => 'nullable|string|max:255',
            'tasks.*.priority' => 'nullable|string|max:255',
            'tasks.*.status' => 'nullable|string|max:255',
            'tasks.*.due_date' => 'nullable|date',

            // Documents
            'other_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'resignation_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',

            // Signatures
            'handover_signature' => 'required|string',
            'takeover_signature' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file uploads
            $otherDocumentsPath = null;
            if ($request->hasFile('other_documents')) {
                $file = $request->file('other_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '_other.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/handover');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $otherDocumentsPath = 'employeedocuments/handover/' . $fileName;
            }

            $resignationDocumentsPath = null;
            if ($request->hasFile('resignation_documents')) {
                $file = $request->file('resignation_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '_resignation.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/handover');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $resignationDocumentsPath = 'employeedocuments/handover/' . $fileName;
            }

            // Handle signatures
            $handoverSignaturePath = $this->processSignature($request->handover_signature, 'handover_');
            $takeoverSignaturePath = $this->processSignature($request->takeover_signature, 'takeover_');

            // Create handover record
            Handover::create([
                'handover_employee_id' => $request->handover_employee_id,
                'handover_employee_email' => $request->handover_employee_email,
                'handover_department' => $request->handover_department_id,
                'handover_designation' => $request->handover_designation_id,
                'takeover_employee_id' => $request->takeover_employee_id,
                'takeover_employee_email' => $request->takeover_employee_email,
                'takeover_department' => $request->takeover_department_id,
                'takeover_designation' => $request->takeover_designation_id,
                'reason' => $request->reason,
                'reason_other' => $request->reason_other,
                'handover_date' => $request->handover_date,
                'tasks' => $request->tasks ? json_encode($request->tasks) : null,
                'other_documents' => $otherDocumentsPath,
                'resignation_documents' => $resignationDocumentsPath,
                'handover_signature' => $handoverSignaturePath,
                'takeover_signature' => $takeoverSignaturePath,
                'delete_status' => 1,
            ]);

            return redirect()->route('handoverlist')
                ->with('success', 'Handover record created successfully!');
        } catch (\Exception $e) {
            \Log::error('Handover creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating handover record: ' . $e->getMessage())
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
            $destinationPath = public_path('employeesignatures/handover');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            file_put_contents($destinationPath . '/' . $fileName, $decoded);
            return 'employeesignatures/handover/' . $fileName;
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
                'image'
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
            $handover = Handover::with([
                'handoverEmployee',
                'takeoverEmployee',
                'handoverDepartmentRelation',
                'handoverDesignationRelation',
                'takeoverDepartmentRelation',
                'takeoverDesignationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.handover.show', compact('handover'));
        } catch (\Exception $e) {
            \Log::error('Error fetching handover details: ' . $e->getMessage());
            return redirect()->route('handoverlist')
                ->with('error', 'Handover record not found or unable to load details.');
        }
    }

    public function edit($id)
    {
        try {
            $handover = Handover::with([
                'handoverEmployee',
                'takeoverEmployee',
                'handoverDepartmentRelation',
                'handoverDesignationRelation',
                'takeoverDepartmentRelation',
                'takeoverDesignationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.handover.edit', compact('handover'));
        } catch (\Exception $e) {
            \Log::error('Error fetching handover for edit: ' . $e->getMessage());
            return redirect()->route('handoverlist')
                ->with('error', 'Handover record not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'handover_employee_id' => 'required|exists:employees,emp_id',
            'takeover_employee_id' => 'required|exists:employees,emp_id',
            'reason' => 'nullable|integer|in:1,2,3,4,0',
            'reason_other' => 'nullable|required_if:reason,0|string|max:255',

            'handover_date' => 'required|date',

            // Task validation
            'tasks.*.task_no' => 'nullable|string|max:255',
            'tasks.*.task_name' => 'nullable|string|max:255',
            'tasks.*.priority' => 'nullable|string|max:255',
            'tasks.*.status' => 'nullable|string|max:255',
            'tasks.*.due_date' => 'nullable|date',

            // Documents
            'other_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'resignation_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',

            // Signatures
            'handover_signature_data' => 'nullable|string',
            'handover_signature_delete' => 'nullable|in:0,1',
            'takeover_signature_data' => 'nullable|string',
            'takeover_signature_delete' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $handover = Handover::findOrFail($id);

            // Handle file uploads
            $otherDocumentsPath = $handover->other_documents;
            if ($request->hasFile('other_documents')) {
                // Delete old file if exists
                if ($otherDocumentsPath && file_exists(public_path($otherDocumentsPath))) {
                    unlink(public_path($otherDocumentsPath));
                }

                $file = $request->file('other_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '_other.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/handover');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $otherDocumentsPath = 'employeedocuments/handover/' . $fileName;
            }

            $resignationDocumentsPath = $handover->resignation_documents;
            if ($request->hasFile('resignation_documents')) {
                // Delete old file if exists
                if ($resignationDocumentsPath && file_exists(public_path($resignationDocumentsPath))) {
                    unlink(public_path($resignationDocumentsPath));
                }

                $file = $request->file('resignation_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '_resignation.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/handover');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $resignationDocumentsPath = 'employeedocuments/handover/' . $fileName;
            }

            // Handle handover signature
            $handoverSignaturePath = $handover->handover_signature;
            if ($request->input('handover_signature_delete') == '1') {
                if ($handoverSignaturePath && file_exists(public_path($handoverSignaturePath))) {
                    unlink(public_path($handoverSignaturePath));
                }
                $handoverSignaturePath = null;
            }

            if ($request->filled('handover_signature_data')) {
                // Delete old signature if exists
                if ($handoverSignaturePath && file_exists(public_path($handoverSignaturePath))) {
                    unlink(public_path($handoverSignaturePath));
                }

                $handoverSignaturePath = $this->processSignature($request->handover_signature_data, 'handover_');
            }

            // Handle takeover signature
            $takeoverSignaturePath = $handover->takeover_signature;
            if ($request->input('takeover_signature_delete') == '1') {
                if ($takeoverSignaturePath && file_exists(public_path($takeoverSignaturePath))) {
                    unlink(public_path($takeoverSignaturePath));
                }
                $takeoverSignaturePath = null;
            }

            if ($request->filled('takeover_signature_data')) {
                // Delete old signature if exists
                if ($takeoverSignaturePath && file_exists(public_path($takeoverSignaturePath))) {
                    unlink(public_path($takeoverSignaturePath));
                }

                $takeoverSignaturePath = $this->processSignature($request->takeover_signature_data, 'takeover_');
            }

            // Update handover record
            $handover->update([
                'handover_employee_id' => $request->handover_employee_id,
                'handover_employee_email' => $request->handover_employee_email,
                'handover_department' => $request->handover_department_id,
                'handover_designation' => $request->handover_designation_id,
                'takeover_employee_id' => $request->takeover_employee_id,
                'takeover_employee_email' => $request->takeover_employee_email,
                'takeover_department' => $request->takeover_department_id,
                'takeover_designation' => $request->takeover_designation_id,
                'reason' => $request->reason,
                'reason_other' => $request->reason_other,
                'handover_date' => $request->handover_date,
                'tasks' => $request->tasks ? json_encode($request->tasks) : null,
                'other_documents' => $otherDocumentsPath,
                'resignation_documents' => $resignationDocumentsPath,
                'handover_signature' => $handoverSignaturePath,
                'takeover_signature' => $takeoverSignaturePath,
            ]);

            return redirect()->route('handoverlist')
                ->with('success', 'Handover record updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Handover update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating handover record: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $handover = Handover::findOrFail($id);
            $handover->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Handover record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Handover deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting handover record'
            ], 500);
        }
    }
}

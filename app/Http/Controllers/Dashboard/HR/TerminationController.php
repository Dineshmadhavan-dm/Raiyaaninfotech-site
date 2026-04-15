<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Termination;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Handover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TerminationController extends Controller
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
    //     $this->middleware('permission:hr->termination view')->only(['terminationlist', 'show']);
    //     $this->middleware('permission:hr->termination create')->only(['terminationcreate', 'terminationstore']);
    //     $this->middleware('permission:hr->termination edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->termination delete')->only(['destroy']);
    // }



    public function terminationlist()
    {
        // STEP 2: Show only confirm_termination = 0 (No) records
        $terminations = Termination::with([
            'employee',
            'departmentRelation',
            'designationRelation',
            'proposedDesignation'
        ])
            ->where('delete_status', 1)
            ->where('confirm_termination', 0) // Only show unconfirmed terminations
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.termination.list', compact('terminations', 'employees', 'departments'));
    }

    public function terminationcreate()
    {
        return view('dashboard.hr.termination.create');
    }

    public function terminationstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'proposed_designation_id' => 'required|exists:designations,des_id',
            'termination_type' => 'required|boolean',
            'reason_for_termination' => 'required|string',
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'employee_statement' => 'nullable|string',
            'can_be_rehired' => 'required|boolean',
            'confirm_termination' => 'required|boolean',
            'rehire_conditions' => 'nullable|string|required_if:can_be_rehired,1',
            'full_name' => 'nullable|string',
            'date_of_hire' => 'required|date',
            'termination_date' => 'required|date',
            'acknowledgement' => 'required|boolean',
            'employee_signature' => 'nullable|string',
            'manager_signature' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file upload
            $supportingDocumentsPath = null;
            if ($request->hasFile('supporting_documents')) {
                $file = $request->file('supporting_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/termination');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/termination/' . $fileName;
            }

            // Handle signatures
            $employeeSignaturePath = $this->processSignature($request->employee_signature, 'employee_');
            $managerSignaturePath = $this->processSignature($request->manager_signature, 'manager_');

            // Create termination record
            $termination =  Termination::create([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_id,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'proposed_designation' => $request->proposed_designation_id,
                'termination_type' => $request->termination_type,
                'reason_for_termination' => $request->reason_for_termination,
                'supporting_documents' => $supportingDocumentsPath,
                'employee_statement' => $request->employee_statement,
                'can_be_rehired' => $request->can_be_rehired,
                'confirm_termination' => $request->confirm_termination,
                'rehire_conditions' => $request->rehire_conditions,
                'full_name' => $request->employee_id,
                'date_of_hire' => $request->date_of_hire,
                'termination_date' => $request->termination_date,
                'acknowledgement' => $request->acknowledgement,
                'employee_signature' => $employeeSignaturePath,
                'manager_signature' => $managerSignaturePath,
                'delete_status' => 1,
            ]);


            // STEP 1: If confirm_termination is Yes (1), create handover record
            if ($request->confirm_termination == 1) {
                $this->createHandoverFromTermination($termination, $request);
            }

            return redirect()->route('terminationlist')
                ->with('success', 'Termination record created successfully!');
        } catch (\Exception $e) {
            \Log::error('Termination creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating termination record: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function createHandoverFromTermination($termination, $request)
    {
        try {
            // Get employee details for handover
            $employee = Employee::where('emp_id', $termination->employee_id)->first();

            if (!$employee) {
                throw new \Exception('Employee not found for handover creation');
            }


            $defaultTasks = [
                [
                    'task_no' => 'T001',
                    'task_name' => 'Application Testing DepthProcess',
                    'priority' => 'High',
                    'status' => 'Pending',
                    'due_date' => $request->handover_date ?? Carbon::now()->addDays(7)->format('Y-m-d'),
                ]
            ];

            // Create handover record
            Handover::create([
                'handover_employee_id' => $termination->employee_id,
                'handover_employee_email' => $termination->employee_id,
                'handover_department' => $termination->department,
                'handover_designation' => $termination->designation,
                'takeover_employee_id' => 1,
                'takeover_employee_email' => null,
                'takeover_department' => null,
                'takeover_designation' => null,
                'reason' => 4,
                'reason_other' =>  null,
                'handover_date' => $termination->termination_date,
                'tasks' => json_encode($defaultTasks), // Use default tasks instead of null
                'other_documents' => null, // Can copy from termination if needed
                'resignation_documents' => $termination->supporting_documents,
                'handover_signature' => $termination->employee_signature,
                'takeover_signature' => $termination->manager_signature,
                'termination_id' => $termination->id, // Add reference to termination
                'delete_status' => 1,
            ]);

            \Log::info('Handover record created automatically from termination ID: ' . $termination->id);
        } catch (\Exception $e) {
            \Log::error('Error creating handover from termination: ' . $e->getMessage());
            throw $e; // Re-throw to handle in main method
        }
    }

    /**
     * Process tasks data for handover
     */


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
            $destinationPath = public_path('employeesignatures/termination');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            file_put_contents($destinationPath . '/' . $fileName, $decoded);
            return 'employeesignatures/termination/' . $fileName;
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
            $termination = Termination::with([
                'employee',
                'departmentRelation',
                'designationRelation',
                'proposedDesignation'
            ])->findOrFail($id);

            return view('dashboard.hr.termination.show', compact('termination'));
        } catch (\Exception $e) {
            \Log::error('Error fetching termination details: ' . $e->getMessage());
            return redirect()->route('terminationlist')
                ->with('error', 'Termination record not found or unable to load details.');
        }
    }

    public function edit($id)
    {
        try {
            $termination = Termination::with([
                'employee',
                'departmentRelation',
                'designationRelation',
                'proposedDesignation'
            ])->findOrFail($id);

            return view('dashboard.hr.termination.edit', compact('termination'));
        } catch (\Exception $e) {
            \Log::error('Error fetching termination for edit: ' . $e->getMessage());
            return redirect()->route('terminationlist')
                ->with('error', 'Termination record not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'proposed_designation_id' => 'required|exists:designations,des_id',
            'termination_type' => 'required|boolean',
            'reason_for_termination' => 'required|string',
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'employee_statement' => 'nullable|string',
            'can_be_rehired' => 'required|boolean',
            'confirm_termination' => 'required|boolean',
            'rehire_conditions' => 'nullable|string|required_if:can_be_rehired,1',
            'full_name' => 'required|string',
            'date_of_hire' => 'required|date',
            'acknowledgement' => 'required|boolean',
            'termination_date' => 'required|date',
            'employee_signature_data' => 'nullable|string',
            'employee_signature_delete' => 'nullable|in:0,1',
            'manager_signature_data' => 'nullable|string',
            'manager_signature_delete' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $termination = Termination::findOrFail($id);

            // Handle file upload
            $supportingDocumentsPath = $termination->supporting_documents;
            if ($request->hasFile('supporting_documents')) {
                // Delete old file if exists
                if ($supportingDocumentsPath && file_exists(public_path($supportingDocumentsPath))) {
                    unlink(public_path($supportingDocumentsPath));
                }

                $file = $request->file('supporting_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/termination');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/termination/' . $fileName;
            }

            // Handle employee signature
            $employeeSignaturePath = $termination->employee_signature;
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

            // Handle manager signature
            $managerSignaturePath = $termination->manager_signature;
            if ($request->input('manager_signature_delete') == '1') {
                if ($managerSignaturePath && file_exists(public_path($managerSignaturePath))) {
                    unlink(public_path($managerSignaturePath));
                }
                $managerSignaturePath = null;
            }

            if ($request->filled('manager_signature_data')) {
                // Delete old signature if exists
                if ($managerSignaturePath && file_exists(public_path($managerSignaturePath))) {
                    unlink(public_path($managerSignaturePath));
                }

                $managerSignaturePath = $this->processSignature($request->manager_signature_data, 'manager_');
            }

            // Update termination record
            $termination->update([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_id,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'proposed_designation' => $request->proposed_designation_id,
                'termination_type' => $request->termination_type,
                'reason_for_termination' => $request->reason_for_termination,
                'supporting_documents' => $supportingDocumentsPath,
                'employee_statement' => $request->employee_statement,
                'can_be_rehired' => $request->can_be_rehired,
                'confirm_termination' => $request->confirm_termination,
                'rehire_conditions' => $request->rehire_conditions,
                'full_name' => $request->employee_id,
                'acknowledgement' => $request->acknowledgement,
                'date_of_hire' => $request->date_of_hire,
                'termination_date' => $request->termination_date,
                'employee_signature' => $employeeSignaturePath,
                'manager_signature' => $managerSignaturePath,
            ]);
            if ($request->confirm_termination == 1) {
                $this->createHandoverFromTermination($termination, $request);
            }
            return redirect()->route('terminationlist')
                ->with('success', 'Termination record updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Termination update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating termination record: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $termination = Termination::findOrFail($id);
            $termination->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Termination record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Termination deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting termination record'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Probation;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Promotion;
use App\Models\Termination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProbationController extends Controller
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
    //     $this->middleware('permission:hr->probation view')->only(['probationlist', 'show']);
    //     $this->middleware('permission:hr->probation create')->only(['probationcreate', 'probationstore']);
    //     $this->middleware('permission:hr->probation edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->probation delete')->only(['destroy']);
    // }



    public function probationlist()
    {
        $probations = Probation::with([
            'employee',
            'departmentRelation',
            'designationRelation'
        ])
            ->where('delete_status', 1)
            ->whereNotIn('appropriate_option', [1, 3]) // 🚀 exclude confirmed values
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.probation.list', compact('probations', 'employees', 'departments'));
    }


    public function probationcreate()
    {
        return view('dashboard.hr.probation.create');
    }

    public function probationstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'supervisor_name' => 'required|string|max:255',
            'probation_from' => 'required|date',
            'date_of_joined' => 'required|date',
            'probation_to' => 'required|date|after:probation_from',

            // Evaluation scores
            'knowledge_score' => 'required|integer|between:1,3',
            'skills_score' => 'required|integer|between:1,3',
            'quality_score' => 'required|integer|between:1,3',
            'productivity_score' => 'required|integer|between:1,3',
            'teamwork_score' => 'required|integer|between:1,3',
            'punctuality_score' => 'required|integer|between:1,3',
            'dependability_score' => 'required|integer|between:1,3',
            'communication_score' => 'required|integer|between:1,3',

            // Evaluation notes
            'knowledge_notes' => 'nullable|string',
            'skills_notes' => 'nullable|string',
            'quality_notes' => 'nullable|string',
            'productivity_notes' => 'nullable|string',
            'teamwork_notes' => 'nullable|string',
            'punctuality_notes' => 'nullable|string',
            'dependability_notes' => 'nullable|string',
            'communication_notes' => 'nullable|string',

            // Supporting documents
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',

            // Evaluation result
            'overall_rating' => 'required|integer|between:1,3',
            'appropriate_option' => 'required|integer|between:1,3',

            // Additional information
            'comments' => 'nullable|string',
            'hr_signature' => 'required|string',
            'date_of_evaluation' => 'required|date',

            'full_name' => 'required|string',
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
                $destinationPath = public_path('employeedocuments/probation');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/probation/' . $fileName;
            }

            // Handle signature
            $hrSignaturePath = $this->processSignature($request->hr_signature);

            // Create probation record
            $probation = Probation::create([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_email,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'supervisor_name' => $request->supervisor_name,
                'probation_from' => $request->probation_from,
                'date_of_joined' => $request->date_of_joined,
                'probation_to' => $request->probation_to,

                // Evaluation scores
                'knowledge_score' => $request->knowledge_score,
                'skills_score' => $request->skills_score,
                'quality_score' => $request->quality_score,
                'productivity_score' => $request->productivity_score,
                'teamwork_score' => $request->teamwork_score,
                'punctuality_score' => $request->punctuality_score,
                'dependability_score' => $request->dependability_score,
                'communication_score' => $request->communication_score,

                // Evaluation notes
                'knowledge_notes' => $request->knowledge_notes,
                'skills_notes' => $request->skills_notes,
                'quality_notes' => $request->quality_notes,
                'productivity_notes' => $request->productivity_notes,
                'teamwork_notes' => $request->teamwork_notes,
                'punctuality_notes' => $request->punctuality_notes,
                'dependability_notes' => $request->dependability_notes,
                'communication_notes' => $request->communication_notes,

                // Supporting documents
                'supporting_documents' => $supportingDocumentsPath,

                // Evaluation result
                'overall_rating' => $request->overall_rating,
                'appropriate_option' => $request->appropriate_option,

                // Additional information
                'comments' => $request->comments,
                'hr_signature' => $hrSignaturePath,
                'date_of_evaluation' => $request->date_of_evaluation,

                'full_name' => $request->full_name,
                'delete_status' => 1,
            ]);

            // ✅ If confirmed (appropriate_option = 1), create Promotion entry
            if ($request->appropriate_option == 1) {
                Promotion::create([
                    'employee_id' => $request->employee_id,
                    'emp_email' => $request->employee_email,
                    'department' => $request->department_id,
                    'designation' => $request->designation_id, // current designation
                    'proposed_designation' => $request->designation_id, // same as current if no change
                    'percentage_increase' => 0, // default, you can change logic later
                    'supporting_documents' => $supportingDocumentsPath,
                    'additional_comments' => "Auto-created from probation confirmation",
                    'manager_name' => $request->supervisor_name,
                    'date_of_signature' => Carbon::now()->toDateString(),
                    'full_name' => $request->full_name,
                    'date_of_hire' => $request->date_of_joined,
                    'proposed_new_salary' => '₹0', // you can calculate/leave blank
                    'reason_for_promotion' => 'Probation confirmed',
                    'manager_signature' => $hrSignaturePath,
                    'delete_status' => 1,
                ]);
            }
            // 🚀 STEP 1: If terminated (appropriate_option = 3), create Termination entry
            if ($request->appropriate_option == 3) {
                $this->createTerminationRecord($request, $supportingDocumentsPath, $hrSignaturePath);
            }

            return redirect()->route('probationlist')
                ->with('success', 'Probation record created successfully!');
        } catch (\Exception $e) {
            \Log::error('Probation creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating probation record: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Create termination record when probation is terminated
     */
    private function createTerminationRecord($request, $supportingDocumentsPath, $hrSignaturePath)
    {
        try {
            // Get employee details for additional information
            $employee = Employee::where('emp_id', $request->employee_id)->first();

            if (!$employee) {
                \Log::warning("Employee not found for termination: " . $request->employee_id);
                return;
            }

            // Create termination record
            Termination::create([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_email ?? $employee->email_company,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'proposed_designation' => $request->designation_id, // Same as current designation
                'termination_type' => 0, // 0 for involuntary (termination due to failed probation)
                'reason_for_termination' => 'Terminated due to unsatisfactory performance ',
                'supporting_documents' => $supportingDocumentsPath,
                'employee_statement' => 'Auto-generated from probation termination',
                'can_be_rehired' => 0, // Usually not rehirable after probation termination
                'confirm_termination' => 0, // Usually not confirm_termination after probation termination
                'rehire_conditions' => null,
                'full_name' => $request->full_name ?? $employee->fullname,
                'date_of_hire' => $request->date_of_joined,
                'termination_date' => Carbon::now()->toDateString(), // Termination effective immediately
                'acknowledgement' => 1, // Auto-acknowledged by system
                'employee_signature' => null, // Employee may not sign immediately
                'manager_signature' => $hrSignaturePath, // Use HR signature from probation
                'delete_status' => 1,
            ]);

            \Log::info("Termination record created for employee: " . $request->employee_id . " via probation termination");
        } catch (\Exception $e) {
            \Log::error('Error creating termination record from probation: ' . $e->getMessage());
            throw $e; // Re-throw to handle in main method
        }
    }


    private function processSignature($signatureData)
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
            $fileName =  $currentDateTime . '.' . $type;
            $destinationPath = public_path('employeesignatures/probation');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            file_put_contents($destinationPath . '/' . $fileName, $decoded);
            return 'employeesignatures/probation/' . $fileName;
        } catch (\Exception $e) {
            \Log::error('Signature processing error: ' . $e->getMessage());
            return null;
        }
    }










    // Update the getDepartmentAdminName method in ProbationController
    private function getDepartmentAdminName($departmentId)
    {
        if (!$departmentId) {
            return $this->getSuperAdminName();
        }

        // Try to get department admin
        $admin = User::role('Admin')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('cur_department', $departmentId);
            })
            ->with('employee')
            ->first();

        if ($admin && $admin->employee) {
            return $admin->employee->fullname; // Remove [Admin] suffix
        }

        // Fallback to super admin
        return $this->getSuperAdminName();
    }

    // Update the getSuperAdminName method to return only name
    private function getSuperAdminName()
    {
        $superadmin = User::role('Super admin')->first();
        return $superadmin ? $superadmin->name : 'No admin assigned'; // Remove [Super admin] suffix
    }



    // Update the search method to include both display and raw names
    // Update the search method in ProbationController
    public function search(Request $request)
    {
        $searchTerm = $request->input('term');

        // Get employees who DON'T have active probation records
        $employees = Employee::with('Departmentid', 'Designationid')
            ->where(function ($query) use ($searchTerm) {
                $query->where('fullname', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%");
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('probations')
                    ->whereColumn('probations.employee_id', 'employees.emp_id')
                    ->where('probations.delete_status', 1)
                    ->whereIn('probations.appropriate_option', [1, 2, 3]); // Exclude confirmed, extended, terminated
            })
            ->select(
                'emp_id',
                'fullname',
                'employee_id',
                'email_company as email',
                'cur_department',
                'cur_designation',
                'image',
                'dojprovision_from_date',
                'provision_to_date'
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

                // Get supervisor information
                $supervisorInfo = $this->getSupervisorInfo($employee);
                $employee->supervisor_name = $supervisorInfo['name'];
                $employee->supervisor_display = $supervisorInfo['display'];

                return $employee;
            });

        return response()->json($employees);
    }

    // Add this new method to handle supervisor logic
    private function getSupervisorInfo($employee)
    {
        $departmentId = $employee->Departmentid->dep_id ?? null;

        if (!$departmentId) {
            $superAdminName = $this->getSuperAdminName();
            return [
                'name' => $superAdminName,
                'display' => $superAdminName ? $superAdminName . ' [Super admin]' : 'No admin assigned'
            ];
        }

        // Try to get department admin
        $admin = User::role('Admin')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('cur_department', $departmentId);
            })
            ->with('employee')
            ->first();

        if ($admin && $admin->employee) {
            // Check if employee is the same as department admin
            if ($admin->employee->emp_id == $employee->emp_id) {
                // Employee is the department admin, so show Super Admin as supervisor
                $superAdminName = $this->getSuperAdminName();
                return [
                    'name' => $superAdminName,
                    'display' => $superAdminName ? $superAdminName . ' [Super admin]' : 'No admin assigned'
                ];
            } else {
                // Different person, show department admin
                return [
                    'name' => $admin->employee->fullname,
                    'display' => $admin->employee->fullname . ' [Admin]'
                ];
            }
        }

        // Fallback to super admin
        $superAdminName = $this->getSuperAdminName();
        return [
            'name' => $superAdminName,
            'display' => $superAdminName ? $superAdminName . ' [Super admin]' : 'No admin assigned'
        ];
    }

    // Keep these methods as before


    // Add this new method for display purposes in search results
    private function getDepartmentAdminNameWithRole($departmentId)
    {
        if (!$departmentId) {
            return $this->getSuperAdminNameWithRole();
        }

        // Try to get department admin
        $admin = User::role('Admin')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('cur_department', $departmentId);
            })
            ->with('employee')
            ->first();

        if ($admin && $admin->employee) {
            return $admin->employee->fullname . ' [Admin]';
        }

        // Fallback to super admin
        return $this->getSuperAdminNameWithRole();
    }

    // Method to get super admin name with role for display
    private function getSuperAdminNameWithRole()
    {
        $superadmin = User::role('Super admin')->first();
        return $superadmin ? $superadmin->name . ' [Super admin]' : 'No admin assigned';
    }



















    public function show($id)
    {
        try {
            $probation = Probation::with([
                'employee',
                'departmentRelation',
                'designationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.probation.show', compact('probation'));
        } catch (\Exception $e) {
            \Log::error('Error fetching probation details: ' . $e->getMessage());
            return redirect()->route('probationlist')
                ->with('error', 'Probation record not found or unable to load details.');
        }
    }

    public function edit($id)
    {
        try {
            $probation = Probation::with([
                'employee',
                'departmentRelation',
                'designationRelation'
            ])->findOrFail($id);

            return view('dashboard.hr.probation.edit', compact('probation'));
        } catch (\Exception $e) {
            \Log::error('Error fetching probation for edit: ' . $e->getMessage());
            return redirect()->route('probationlist')
                ->with('error', 'Probation record not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'employee_email' => 'nullable|email',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'supervisor_name' => 'required|string|max:255',
            'probation_from' => 'required|date',
            'date_of_joined' => 'required|date',
            'probation_to' => 'required|date|after:probation_from',

            // Evaluation scores
            'knowledge_score' => 'required|integer|between:1,3',
            'skills_score' => 'required|integer|between:1,3',
            'quality_score' => 'required|integer|between:1,3',
            'productivity_score' => 'required|integer|between:1,3',
            'teamwork_score' => 'required|integer|between:1,3',
            'punctuality_score' => 'required|integer|between:1,3',
            'dependability_score' => 'required|integer|between:1,3',
            'communication_score' => 'required|integer|between:1,3',

            // Evaluation notes
            'knowledge_notes' => 'nullable|string',
            'skills_notes' => 'nullable|string',
            'quality_notes' => 'nullable|string',
            'productivity_notes' => 'nullable|string',
            'teamwork_notes' => 'nullable|string',
            'punctuality_notes' => 'nullable|string',
            'dependability_notes' => 'nullable|string',
            'communication_notes' => 'nullable|string',

            // Supporting documents
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',

            // Evaluation result
            'overall_rating' => 'required|integer|between:1,3',
            'appropriate_option' => 'required|integer|between:1,3',

            // Additional information
            'comments' => 'nullable|string',
            'hr_signature_data' => 'nullable|string',
            'hr_signature_delete' => 'nullable|in:0,1',
            'date_of_evaluation' => 'required|date',

            'full_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $probation = Probation::findOrFail($id);

            // Handle file upload
            $supportingDocumentsPath = $probation->supporting_documents;
            if ($request->hasFile('supporting_documents')) {
                // Delete old file if exists
                if ($supportingDocumentsPath && file_exists(public_path($supportingDocumentsPath))) {
                    unlink(public_path($supportingDocumentsPath));
                }

                $file = $request->file('supporting_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/probation');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/probation/' . $fileName;
            }

            // Handle HR signature
            $hrSignaturePath = $probation->hr_signature;
            if ($request->input('hr_signature_delete') == '1') {
                if ($hrSignaturePath && file_exists(public_path($hrSignaturePath))) {
                    unlink(public_path($hrSignaturePath));
                }
                $hrSignaturePath = null;
            }

            if ($request->filled('hr_signature_data')) {
                // Delete old signature if exists
                if ($hrSignaturePath && file_exists(public_path($hrSignaturePath))) {
                    unlink(public_path($hrSignaturePath));
                }

                $hrSignaturePath = $this->processSignature($request->hr_signature_data);
            }

            // Update probation record
            $probation->update([
                'employee_id' => $request->employee_id,
                'employee_email' => $request->employee_email,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'supervisor_name' => $request->supervisor_name,
                'probation_from' => $request->probation_from,
                'date_of_joined' => $request->date_of_joined,
                'probation_to' => $request->probation_to,

                // Evaluation scores
                'knowledge_score' => $request->knowledge_score,
                'skills_score' => $request->skills_score,
                'quality_score' => $request->quality_score,
                'productivity_score' => $request->productivity_score,
                'teamwork_score' => $request->teamwork_score,
                'punctuality_score' => $request->punctuality_score,
                'dependability_score' => $request->dependability_score,
                'communication_score' => $request->communication_score,

                // Evaluation notes
                'knowledge_notes' => $request->knowledge_notes,
                'skills_notes' => $request->skills_notes,
                'quality_notes' => $request->quality_notes,
                'productivity_notes' => $request->productivity_notes,
                'teamwork_notes' => $request->teamwork_notes,
                'punctuality_notes' => $request->punctuality_notes,
                'dependability_notes' => $request->dependability_notes,
                'communication_notes' => $request->communication_notes,

                // Supporting documents
                'supporting_documents' => $supportingDocumentsPath,

                // Evaluation result
                'overall_rating' => $request->overall_rating,
                'appropriate_option' => $request->appropriate_option,

                // Additional information
                'comments' => $request->comments,
                'hr_signature' => $hrSignaturePath,
                'date_of_evaluation' => $request->date_of_evaluation,

                'full_name' => $request->full_name,
            ]);

            return redirect()->route('probationlist')
                ->with('success', 'Probation record updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Probation update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating probation record: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $probation = Probation::findOrFail($id);
            $probation->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Probation record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Probation deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting probation record'
            ], 500);
        }
    }
}

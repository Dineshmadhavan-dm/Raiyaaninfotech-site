<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Promotion;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromotionController extends Controller
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
    //     $this->middleware('permission:hr->promotion view')->only(['promotionlist', 'show']);
    //     $this->middleware('permission:hr->promotion create')->only(['promotioncreate', 'promotionstore']);
    //     $this->middleware('permission:hr->promotion edit')->only(['edit', 'update']);
    //     $this->middleware('permission:hr->promotion delete')->only(['destroy']);
    // }


    public function prolist()
    {
        $promotions = Promotion::with([
            'employeeid',
            'departmentRelation',
            'currentDesignation',
            'proposedDesignation'
        ])
            ->where('delete_status', 1)
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.promotion.list', compact('promotions', 'employees', 'departments'));
    }

    public function proedit($id)
    {
        $promotion = Promotion::with([
            'employeeid',
            'departmentRelation',
            'currentDesignation',
            'proposedDesignation'
        ])->findOrFail($id);

        $employee = $promotion->employeeid;
        $department = $promotion->departmentRelation;
        $currentDesignation = $promotion->currentDesignation;
        $proposedDesignation = $promotion->proposedDesignation;

        return view('dashboard.hr.promotion.edit', compact(
            'promotion',
            'employee',
            'department',
            'currentDesignation',
            'proposedDesignation'
        ));
    }

    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->update([
                'delete_status' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Promotion deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Promotion deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting promotion'
            ], 500);
        }
    }

    public function proupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'emp_email' => 'nullable|string',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'proposed_designation_id' => 'required|exists:designations,des_id',
            'percentage_increase' => 'required|numeric|min:0|max:100',
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'additional_comments' => 'nullable|string',
            'manager_name' => 'required|string|max:255',
            'date_of_signature' => 'required|date',
            'full_name' => 'nullable|string|max:255',
            'date_of_hire' => 'required|date',
            'proposed_new_salary' => 'required|string|max:255',
            'reason_for_promotion' => 'required|string',
            'manager_signature_data' => 'nullable|string',
            'manager_signature_delete' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $promotion = Promotion::findOrFail($id);

            // ----------------------------
            // Handle supporting documents
            // ----------------------------
            $supportingDocumentsPath = $promotion->supporting_documents;
            if ($request->hasFile('supporting_documents')) {
                if ($supportingDocumentsPath && file_exists(public_path($supportingDocumentsPath))) {
                    unlink(public_path($supportingDocumentsPath));
                }

                $file = $request->file('supporting_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/promotion');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/promotion/' . $fileName;
            }

            // ----------------------------
            // Handle manager signature
            // ----------------------------
            $signaturePath = $promotion->manager_signature;

            if ($request->input('manager_signature_delete') == '1') {
                if ($signaturePath && file_exists(public_path($signaturePath))) {
                    unlink(public_path($signaturePath));
                }
                $signaturePath = null;
            }

            if ($request->filled('manager_signature_data')) {
                $signatureData = $request->input('manager_signature_data');

                if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
                    $data = substr($signatureData, strpos($signatureData, ',') + 1);
                    $type = strtolower($type[1]);
                } else {
                    $data = $signatureData;
                    $type = 'png';
                }

                $decoded = base64_decode($data);
                if ($decoded === false) {
                    return redirect()->back()->with('error', 'Invalid signature data.')->withInput();
                }

                if ($signaturePath && file_exists(public_path($signaturePath))) {
                    unlink(public_path($signaturePath));
                }

                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $type;
                $destinationPath = public_path('employeesignatures/promotion');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                file_put_contents($destinationPath . '/' . $fileName, $decoded);
                $signaturePath = 'employeesignatures/promotion/' . $fileName;
            }

            // ----------------------------
            // Update promotion record
            // ----------------------------
            $promotion->update([
                'employee_id' => $request->employee_id,
                'emp_email' => $request->emp_email,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'proposed_designation' => $request->proposed_designation_id,
                'percentage_increase' => $request->percentage_increase,
                'supporting_documents' => $supportingDocumentsPath,
                'additional_comments' => $request->additional_comments,
                'manager_name' => $request->manager_name,
                'date_of_signature' => $request->date_of_signature,
                'full_name' => $request->full_name,
                'date_of_hire' => $request->date_of_hire,
                'proposed_new_salary' => $request->proposed_new_salary,
                'reason_for_promotion' => $request->reason_for_promotion,
                'manager_signature' => $signaturePath,
            ]);

            return redirect()->route('prolist')->with('success', 'Promotion updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Promotion update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating promotion: ' . $e->getMessage())->withInput();
        }
    }

    public function proshow($id)
    {
        try {
            $promotion = Promotion::with([
                'employeeid',
                'departmentRelation',
                'currentDesignation',
                'proposedDesignation'
            ])->findOrFail($id);

            return view('dashboard.hr.promotion.show', compact('promotion'));
        } catch (\Exception $e) {
            \Log::error('Error fetching promotion details: ' . $e->getMessage());
            return redirect()->route('prolist')
                ->with('error', 'Promotion not found or unable to load details.');
        }
    }

    public function procreate()
    {
        return view('dashboard.hr.promotion.create');
    }

    public function prostore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,emp_id',
            'emp_email' => 'string',
            'department_id' => 'required|exists:departments,dep_id',
            'designation_id' => 'required|exists:designations,des_id',
            'proposed_designation_id' => 'required|exists:designations,des_id',
            'percentage_increase' => 'required|numeric|min:0|max:100',
            'supporting_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'additional_comments' => 'nullable|string',
            'manager_name' => 'required|string|max:255',
            'date_of_signature' => 'required|date',
            'full_name' => 'string|max:255',
            'date_of_hire' => 'required|date',
            'proposed_new_salary' => 'required|string|max:255',
            'reason_for_promotion' => 'required|string',
            'manager_signature' => 'required|string',
        ]);

        try {
            $supportingDocumentsPath = null;
            if ($request->hasFile('supporting_documents')) {
                $file = $request->file('supporting_documents');
                $currentDateTime = Carbon::now()->format('dmYHis');
                $fileName = $currentDateTime . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeedocuments/promotion');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $fileName);
                $supportingDocumentsPath = 'employeedocuments/promotion/' . $fileName;
            }

            $signaturePath = null;
            if ($request->manager_signature) {
                $signatureData = $request->manager_signature;

                if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
                    $data = substr($signatureData, strpos($signatureData, ',') + 1);
                    $type = strtolower($type[1]);

                    if (!in_array($type, ['png', 'jpeg', 'jpg', 'gif'])) {
                        return redirect()->back()
                            ->with('error', 'Invalid signature image format.')
                            ->withInput();
                    }

                    $data = base64_decode($data);
                    if ($data === false) {
                        return redirect()->back()
                            ->with('error', 'Failed to decode signature image.')
                            ->withInput();
                    }
                } else {
                    $data = base64_decode($signatureData);
                    $type = 'png';
                }

                $currentDateTime = Carbon::now()->format('dmYHis');
                $signatureFileName = $currentDateTime . '.' . $type;
                $destinationPath = public_path('employeesignatures/promotion');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                file_put_contents($destinationPath . '/' . $signatureFileName, $data);
                $signaturePath = 'employeesignatures/promotion/' . $signatureFileName;
            }

            Promotion::create([
                'employee_id' => $request->employee_id,
                'emp_email' => $request->emp_email,
                'department' => $request->department_id,
                'designation' => $request->designation_id,
                'proposed_designation' => $request->proposed_designation_id,
                'percentage_increase' => $request->percentage_increase,
                'supporting_documents' => $supportingDocumentsPath,
                'additional_comments' => $request->additional_comments,
                'manager_name' => $request->manager_name,
                'date_of_signature' => $request->date_of_signature,
                'full_name' => $request->full_name,
                'date_of_hire' => $request->date_of_hire,
                'proposed_new_salary' => $request->proposed_new_salary,
                'reason_for_promotion' => $request->reason_for_promotion,
                'manager_signature' => $signaturePath,
                'delete_status' => 1,
            ]);

            return redirect()->route('prolist')
                ->with('success', 'Promotion created successfully!');
        } catch (\Exception $e) {
            \Log::error('Promotion creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating promotion: ' . $e->getMessage())
                ->withInput();
        }
    }

    // In PromotionController - update the search method
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

                // Get supervisor information
                $supervisorInfo = $this->getSupervisorInfo($employee);
                $employee->supervisor_name = $supervisorInfo['name'];
                $employee->supervisor_display = $supervisorInfo['display'];

                return $employee;
            });

        return response()->json($employees);
    }

    // Add these methods to PromotionController
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

    private function getSuperAdminName()
    {
        $superadmin = User::role('Super admin')->first();
        return $superadmin ? $superadmin->name : null;
    }

    public function searchdesignation(Request $request)
    {
        $designations = Designation::select('des_id', 'des_name')
            ->where('delete_status', 1)
            ->orderBy('des_name')
            ->get();

        return response()->json($designations);
    }
}

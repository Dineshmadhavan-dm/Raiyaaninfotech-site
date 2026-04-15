<?php

namespace App\Http\Controllers\Dashboard\HR;



use App\Http\Controllers\Controller;
use App\Models\Branch;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Emprole;

use Illuminate\Http\Request;

use App\Imports\EmployeesImport;
use App\Models\BloodGroup;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Employee;
use App\Models\Family;
use App\Models\Education;
use App\Models\Leave;
use App\Models\Leavetype;
use App\Models\Pastemployee;
use App\Models\Probation;
use App\Models\Professionalreference;
use App\Models\Qualification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;



class EmploymentController extends Controller
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



    public function empshow($id)
    {
        $personal = Employee::with([
            'families',
            'educations',
            'pastemps',
            'prorefs',
            'user.roles.permissions', // Load user with roles and permissions



        ])->where('emp_id', $id)
            ->where('delete_status', 1)
            ->firstOrFail();

        return view('dashboard.hr.employee.show', compact('personal'));
    }








    public function getLeaveSummary($id)
    {
        try {
            // Get leave types assigned to this employee
            $leaveTypes = Leavetype::where('delete_status', 1)
                ->where('employee_name_id', $id)
                ->get();

            $summary = [];

            foreach ($leaveTypes as $leaveType) {
                // Calculate used days for this leave type
                $usedDays = Leave::where('employee_id', $id)
                    ->where('leave_type_id', $leaveType->leavetype_id)

                    ->whereBetween('leavedate_no', [$leaveType->leave_start_from, $leaveType->leave_end_to])
                    ->count();

                $remainingDays = $leaveType->leave_days - $usedDays;

                $summary[] = [
                    'leave_type' => $leaveType->leavetype_name_text,
                    'total_days' => $leaveType->leave_days,
                    'available_days' => $remainingDays,
                    'used_days' => $usedDays
                ];
            }

            return response()->json(['success' => true, 'summary' => $summary]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }











    public function empdestroy(Request $request)
    {
        try {
            $emp = Employee::with(['educations', 'families', 'prorefs', 'pastemps', 'currentemps'])
                ->findOrFail($request->emp_id);

            $emp->update(['delete_status' => 0]);

            foreach ($emp->educations as $education) {
                $education->update(['delete_status' => 0]);
            }

            foreach ($emp->families as $family) {
                $family->update(['delete_status' => 0]);
            }

            foreach ($emp->prorefs as $proref) {
                $proref->update(['delete_status' => 0]);
            }

            foreach ($emp->pastemps as $pastemp) {
                $pastemp->update(['delete_status' => 0]);
            }

            foreach ($emp->currentemps as $currentemp) {
                $currentemp->update(['delete_status' => 0]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee and related records soft deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function bulkDelete(Request $request)
    {
        $empIds = $request->input('emp_ids');

        try {
            $employees = Employee::with(['educations', 'families', 'prorefs', 'pastemps', 'currentemps'])
                ->whereIn('emp_id', $empIds)
                ->get();

            foreach ($employees as $emp) {
                $emp->update(['delete_status' => 0]);

                foreach ($emp->educations as $education) {
                    $education->update(['delete_status' => 0]);
                }

                foreach ($emp->families as $family) {
                    $family->update(['delete_status' => 0]);
                }

                foreach ($emp->prorefs as $proref) {
                    $proref->update(['delete_status' => 0]);
                }

                foreach ($emp->pastemps as $pastemp) {
                    $pastemp->update(['delete_status' => 0]);
                }

                foreach ($emp->currentemps as $currentemp) {
                    $currentemp->update(['delete_status' => 0]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }






    public function empcreate($id = null)
    {
        $employee = null;
        $personal = null;
        $families = [];
        $educations = [];
        $pastEmployments = [];

        $references = [];
        $step = 1;

        if ($id) {
            $employee = Employee::findOrFail($id);
            $personal = $employee;
            $families = $employee->families;
            $educations = $employee->educations;
            $pastEmployments = $employee->pastemps;
            $references = $employee->references;

            // Determine which step to show based on status_for_stepform
            $step = $employee->status_for_stepform;
        }

        // Load other data
        $departments = Department::all();
        $designations = Designation::all();

        $locations = Branch::all();

        return view('dashboard.hr.employee.create', compact(
            'employee',
            'personal',
            'families',
            'educations',
            'pastEmployments',

            'references',
            'departments',
            'designations',

            'locations',
            'step'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $import = new EmployeesImport;
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getRowCount();
            $failedRows = $import->getFailedRows();

            $message = "Imported {$importedCount} employees successfully.";

            if (!empty($failedRows)) {
                $failedCount = count($failedRows);
                $message .= " {$failedCount} rows failed to import.";

                // Optionally log failed rows
                Log::warning('Employee import failed rows', $failedRows);
            }

            return redirect()->back()
                ->with('success', $message)
                ->with('import_stats', [
                    'success' => $importedCount,
                    'failed' => count($failedRows)
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function empcreatepost(Request $request)
    {

        $step = $request->input('step');
        $employee_id = $request->input('employee_id');

        switch ($step) {
            case 1: // Personal Details (only fullname, email, mobile, gender required)
                $validatedData = $request->validate([
                    'fullname' => 'required|string|max:255',
                    'personal_email' => 'required|email|unique:employees,personal_email' . ($employee_id ? ',' . $employee_id . ',emp_id' : ''),
                    'personal_mobile' => 'required|string|max:20',
                    'gender' => 'required|in:male,female',
                    // Optional fields
                    'fathername' => 'nullable|string|max:255',
                    'dob' => 'nullable|date',
                    'pancard_no' => 'nullable|string|max:20|min:10',
                    'address' => 'nullable|string',
                    'bloodgroup' => 'nullable|exists:blood_groups,bloodgroup_id',
                    'marital_status' => 'nullable|in:single,married',
                    'aadhaar_no' => 'nullable',
                    'c_person_emergency' => 'nullable|string|max:255',
                    'number_employee' => 'nullable',
                    'relationship' => 'nullable|exists:relationships,relationship_id',
                    'pincode' => 'nullable|string',
                    'flatno' => 'nullable|string',
                    'street' => 'nullable|string',
                    'country' => 'nullable|string',
                    'state' => 'nullable|string',
                    'city' => 'nullable|string',
                    'emergency_contact' => 'nullable|string|max:20',
                    'cropped_image_data' => 'nullable|string|starts_with:data:image/jpeg;base64',
                ]);
                $validatedData['gender'] = $validatedData['gender'] === 'male' ? 0 : 1;
                if (isset($validatedData['marital_status'])) {
                    $validatedData['marital_status'] =
                        $validatedData['marital_status'] === 'single' ? 0 : 1;
                }

                // Image processing remains the same
                $imageData = $request->cropped_image_data;
                if ($imageData) {
                    $extension = explode(';', explode('/', $imageData)[1])[0];
                    $image = base64_decode(explode(',', $imageData)[1]);

                    $folderPath = public_path('employee_images');
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0777, true);
                    }

                    if ($employee_id) {
                        $employee = Employee::findOrFail($employee_id);
                        if ($employee->image && file_exists(public_path($employee->image))) {
                            unlink(public_path($employee->image));
                        }

                        $imageName = $employee_id . '_emp.' . $extension;
                        $imagePath = $imageName;
                        file_put_contents($folderPath . '/' . $imageName, $image);
                        $validatedData['image'] = $imagePath;
                        $employee->update($validatedData);
                    } else {
                        $employee = Employee::create($validatedData);
                        $employee_id = $employee->emp_id;
                        $imageName = $employee_id . '_emp.' . $extension;
                        $imagePath = $imageName;
                        file_put_contents($folderPath . '/' . $imageName, $image);
                        $employee->update(['image' => $imagePath]);
                    }
                } else {
                    if ($employee_id) {
                        Employee::findOrFail($employee_id)->update($validatedData);
                    } else {
                        $employee = Employee::create($validatedData);
                        $employee_id = $employee->emp_id;
                    }
                }


                return response()->json(['employee_id' => $employee_id]);

            case 2: // Family Details (all optional)
                $request->validate([
                    'fa_name.*' => 'nullable|string|max:255',
                    'fa_relation.*' => 'nullable|exists:relationships,relationship_id',
                    'fa_occupation.*' => 'nullable|string|max:255',
                ]);

                $this->updateFamilyDetails($employee_id, $request);
                Employee::where('emp_id', $employee_id)->update(['status_for_stepform' => Employee::STEP_FAMILY]);
                return response()->json(['employee_id' => $employee_id]);

            case 3: // Education Details (all required except edu_to_date)
                $request->validate([
                    'qualification.*' => 'required|exists:qualifications,qua_id',
                    'name_of_institution.*' => 'required|string|max:255',
                    'edu_location.*' => 'required|string|max:255',
                    'edu_from_date.*' => 'required|date',
                    'specialization.*' => 'required|string|max:255',
                    'percentage_grade.*' => 'required|numeric|between:0,100',
                    'edu_to_date.*' => ' required|date',
                    // Optional
                ]);

                $this->updateEducationDetails($employee_id, $request);
                Employee::where('emp_id', $employee_id)->update(['status_for_stepform' => Employee::STEP_EDUCATION]);
                return response()->json(['employee_id' => $employee_id]);

            case 4: // Past Employment (only employed_as required, others conditional)
                $request->validate([
                    'employed_as.*' => 'required|in:0,1',

                    // If Experienced (1), these become required
                    'past_organisation_name.*' => 'required_if:employed_as.*,1|nullable|string|max:255',
                    'past_designation.*'        => 'required_if:employed_as.*,1|nullable|string|max:255',
                    'past_annual_ctc.*'         => 'required_if:employed_as.*,1|nullable|string|max:255',
                    'past_from_date.*'          => 'required_if:employed_as.*,1|nullable|date',
                    'past_to_date.*'            => 'required_if:employed_as.*,1|nullable|date',

                    'has_uan.*'     => 'nullable|in:0,1',
                    'uan_number.*'  => 'required_if:has_uan.*,1|nullable|string|min:12|size:12|regex:/^[0-9]{12}$/',

                    'past_location.*'   => 'nullable|string|max:255',
                    'past_department.*' => 'nullable|string|max:255',
                    'past_role.*'       => 'nullable|string|max:255',
                ]);


                $this->updatePastEmploymentDetails($employee_id, $request);
                Employee::where('emp_id', $employee_id)->update(['status_for_stepform' => Employee::STEP_PAST_EMPLOYMENT]);
                return response()->json(['employee_id' => $employee_id]);

            case 5: // Current Employment (all required)
                $validatedData = $request->validate([


                    'dojprovision_from_date' => 'required|date',
                    'provision_to_date' => 'required|date',
                    'inmonth' => 'required|string',
                    'jobtype' => 'required|exists:jobtypes,jobtype_id',
                    'cur_department' => 'required|exists:departments,dep_id',
                    'cur_designation' => 'required|exists:designations,des_id',
                    'cur_location' => 'required|exists:branches,branch_id',

                    'cur_annual_ctc' => 'required|string',
                ]);

                // Find the employee by emp_id (not employee_id) and update
                $employee = Employee::where('emp_id', $employee_id)->firstOrFail();
                $employee->update($validatedData);

                // Update the step status
                $employee->update(['status_for_stepform' => Employee::STEP_CURRENT_EMPLOYMENT]);

                return response()->json(['employee_id' => $employee_id]);

            case 6: // Professional References (all required)
                $request->validate([
                    'ref_name.*' => 'required|string|max:255',
                    'ref_organization_name.*' => 'required|string|max:255',
                    'ref_designation.*' => 'required|string|max:255',
                    'mobile_no.*' => 'required|string|max:20',
                    'email_ref.*' => 'nullable|email',
                ]);

                $this->updateReferenceDetails($employee_id, $request);
                Employee::where('emp_id', $employee_id)->update(['status_for_stepform' => Employee::STEP_REFERENCES]);

                session()->flash('successalert', 'Successfully completed employment form.');
                return response()->json([
                    'employee_id' => $employee_id,
                    'redirect' => route('emplist')
                ]);

            default:
                return response()->json(['error' => 'Invalid step'], 400);
        }
    }

    // Helper methods remain the same
    private function updateFamilyDetails($employee_id, $request)
    {
        Family::where('employee_id', $employee_id)->delete();
        if ($request->has('fa_name')) {
            foreach ($request->fa_name as $key => $name) {
                Family::create([
                    'employee_id' => $employee_id,
                    'fa_name' => $name,
                    'fa_relation' => $request->fa_relation[$key] ?? null,
                    'fa_occupation' => $request->fa_occupation[$key] ?? null,
                ]);
            }
        }
    }

    private function updateEducationDetails($employee_id, $request)
    {
        Education::where('employee_id', $employee_id)->delete();
        if ($request->has('qualification')) {
            foreach ($request->qualification as $key => $qualification) {
                Education::create([
                    'employee_id' => $employee_id,
                    'qualification' => $qualification,
                    'name_of_institution' => $request->name_of_institution[$key],
                    'edu_location' => $request->edu_location[$key],
                    'edu_from_date' => $request->edu_from_date[$key],
                    'edu_to_date' => $request->edu_to_date[$key] ?? null,
                    'specialization' => $request->specialization[$key],
                    'percentage_grade' => $request->percentage_grade[$key],
                ]);
            }
        }
    }

    private function updatePastEmploymentDetails($employee_id, $request)
    {
        Pastemployee::where('employee_id', $employee_id)->delete();

        if ($request->has('employed_as')) {
            foreach ($request->employed_as as $key => $employedAs) {
                $convertedEmployedAs = (int)$employedAs;
                $convertedHasUan = isset($request->has_uan[$key]) ? (int)$request->has_uan[$key] : null;


                Pastemployee::create([
                    'employee_id' => $employee_id,
                    'employed_as' => $convertedEmployedAs,
                    'past_organisation_name' => $request->past_organisation_name[$key] ?? null,
                    'past_designation' => $request->past_designation[$key] ?? null,
                    'past_annual_ctc' => $request->past_annual_ctc[$key] ?? null,
                    'past_from_date' => $request->past_from_date[$key] ?? null,
                    'past_to_date' => $request->past_to_date[$key] ?? null,
                    'past_location' => $request->past_location[$key] ?? null,
                    'past_department' => $request->past_department[$key] ?? null,
                    'past_role' => $request->past_role[$key] ?? null,
                    'has_uan' => $convertedHasUan,
                    'uan_number' => $request->uan_number[$key] ?? null,
                ]);
            }
        }
    }

    private function updateReferenceDetails($employee_id, $request)
    {
        Professionalreference::where('employee_id', $employee_id)->delete();
        if ($request->has('ref_name')) {
            foreach ($request->ref_name as $key => $name) {
                Professionalreference::create([
                    'employee_id' => $employee_id,
                    'ref_name' => $name,
                    'ref_organization_name' => $request->ref_organization_name[$key],
                    'ref_designation' => $request->ref_designation[$key],
                    'mobile_no' => $request->mobile_no[$key],
                    'email_ref' => $request->email_ref[$key],
                ]);
            }
        }
    }




    public function empedit($id)
    {
        $employee = Employee::with('bloodGroupid',   'families.relationShipid',)->findOrFail($id);
        $step = $employee->status_for_stepform ?? 1;
        // In your controller


        // Load related data
        $families = Family::where('employee_id', $id)->get();
        $educations = Education::where('employee_id', $id)->get();
        $pastEmployments = Pastemployee::where('employee_id', $id)->get();

        $references = Professionalreference::where('employee_id', $id)->get();

        return view('dashboard.hr.employee.edit', compact(
            'employee',
            'step',
            'families',
            'educations',
            'pastEmployments',

            'references'
        ));
    }



    public function empupdate(Request $request, $id)
    {
        $step = $request->input('step');
        $employee = Employee::findOrFail($id);


        // dd($request->all());

        switch ($step) {
            case 1: // Personal Details
                $validatedData = $request->validate([
                    'fullname' => 'required|string|max:255',
                    'fathername' => 'nullable|string|max:255',
                    'personal_email' => 'required|email|unique:employees,personal_email,' . $id . ',emp_id',
                    'personal_mobile' => 'required|string|max:20',
                    'dob' => 'nullable|date',
                    'pancard_no' => 'nullable|string|min:10',
                    'address' => 'nullable|string',
                    'gender' => 'required|in:male,female',

                    'bloodgroup' => 'nullable|exists:blood_groups,bloodgroup_id',
                    'aadhaar_no' => 'nullable',
                    'marital_status' => 'nullable|in:married,single',
                    'c_person_emergency' => 'nullable|string|max:255',
                    'relationship' => 'nullable|exists:relationships,relationship_id',
                    'emergency_contact' => 'nullable|string|max:20',
                    'pincode' => 'nullable|string',
                    'country' => 'nullable|string',
                    'state' => 'nullable|string',
                    'city' => 'nullable|string',
                    'flatno' => 'nullable|string',
                    'street' => 'nullable|string'
                ]);

                if (isset($validatedData['gender'])) {
                    $validatedData['gender'] = $validatedData['gender'] === 'male' ? 0 : 1;
                }

                if (isset($validatedData['marital_status'])) {
                    $validatedData['marital_status'] =
                        $validatedData['marital_status'] === 'single' ? 0 : 1;
                }


                // Process image if uploaded
                if ($request->cropped_image_data) {
                    $imageData = $request->cropped_image_data;
                    $extension = explode(';', explode('/', $imageData)[1])[0];
                    $image = base64_decode(explode(',', $imageData)[1]);

                    // Create directory if it doesn't exist
                    $folderPath = public_path('employee_images');
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0777, true);
                    }

                    // Delete old image if exists
                    if ($employee->image && file_exists(public_path('employee_images/' . $employee->image))) {
                        unlink(public_path('employee_images/' . $employee->image));
                    }

                    // Generate filename with ID (e.g., 1_emp.jpg)
                    $imageName = $id . '_emp.' . $extension;
                    $imagePath = $imageName;
                    $fullPath = $folderPath . '/' . $imageName;

                    // Save the image
                    file_put_contents($fullPath, $image);
                    $validatedData['image'] = $imagePath;
                }


                $employee->update($validatedData);
                return response()->json(['employee_id' => $id]);

            case 2: // Family Details
                $request->validate([
                    'fa_name.*' => 'nullable|string|max:255',
                    'fa_relation.*' => 'nullable|exists:relationships,relationship_id',
                    'fa_occupation.*' => 'nullable|string|max:255',
                ]);

                $this->updateFamilyDetails($id, $request);
                $employee->update(['status_for_stepform' => Employee::STEP_FAMILY]);
                return response()->json(['employee_id' => $id]);

            case 3: // Education Details
                $request->validate([
                    'qualification.*' => 'nullable|exists:qualifications,qua_id',
                    'name_of_institution.*' => 'required|string|max:255',
                    'edu_location.*' => 'required|string|max:255',
                    'edu_from_date.*' => 'required|date',
                    'edu_to_date.*' => 'nullable|date|after_or_equal:edu_from_date.*',
                    'specialization.*' => 'required|string|max:255',
                    'percentage_grade.*' => 'required|numeric|between:0,100',
                ]);

                $this->updateEducationDetails($id, $request);
                $employee->update(['status_for_stepform' => Employee::STEP_EDUCATION]);
                return response()->json(['employee_id' => $id]);

            case 4: // Past Employment
                $request->validate([
                    'employed_as.*' => 'required|in:0,1',

                    'has_uan.*' => 'nullable|in:0,1',
                    'uan_number.*' => 'nullable|string|max:255|min:12',
                ]);

                $this->updatePastEmploymentDetails($id, $request);
                $employee->update(['status_for_stepform' => Employee::STEP_PAST_EMPLOYMENT]);
                return response()->json(['employee_id' => $id]);

            case 5: // Current Employment
                $validatedData = $request->validate([
                    'dojprovision_from_date' => 'required|date',
                    'provision_to_date' => 'required|date',
                    'inmonth' => 'required|string',
                    'jobtype' => 'nullable|exists:jobtypes,jobtype_id',
                    'cur_department' => 'nullable|exists:departments,dep_id',
                    'cur_designation' => 'nullable|exists:designations,des_id',
                    'cur_location' => 'nullable|exists:branches,branch_id',

                    'cur_annual_ctc' => 'required|min:0',
                ]);

                // Update the existing employee (no need for extra query)
                $employee->update($validatedData);
                $employee->update(['status_for_stepform' => Employee::STEP_CURRENT_EMPLOYMENT]);

                return response()->json(['employee_id' => $id]);

            case 6: // Professional References
                $request->validate([
                    'ref_name.*' => 'required|string|max:255',
                    'ref_organization_name.*' => 'required|string|max:255',
                    'ref_designation.*' => 'required|string|max:255',
                    'mobile_no.*' => 'nullable|string|max:20',
                    'email_ref.*' => 'nullable|email',
                ]);

                $this->updateReferenceDetails($id, $request);
                $employee->update(['status_for_stepform' => Employee::STEP_REFERENCES]);

                session()->flash('successalert', 'Employee details updated successfully.');
                return response()->json([
                    'employee_id' => $id,
                    'redirect' => route('emplist')
                ]);

            default:
                return response()->json(['error' => 'Invalid step'], 400);
        }
    }





















    public function employeditcolumn()
    {
        // Define all available columns with their categories
        $allColumns = [

            'personal' => [
                'checkbox' => 'Checkbox',
                'serial' => 'Serial #',
                'emp_id' => 'Employee ID',
                'image' => 'Image',
                'name' => 'Name',
                'fathername' => 'FatherName',
                'address' => 'Address',
                'personal_email' => 'Personal Email',
                'personal_mobile' => 'Personal Mobile No',
                'blood_group' => 'Blood Group',
                'gender' => 'Gender',
                'marital_status' => 'Marital Status',
                'dob' => 'Date of Birth',
                'pancard_no' => 'Pancard No',
                'aadhaar_no' => 'Aadhaar card No',
                'contact_person' => 'Contact Person Name',
                'relation' => 'Relation',
                'emergency_contact' => 'Emergency Contact',

                'employee_data' => 'Employee Data',
                'action' => 'Action',
            ],
            'family' => [
                'family_member_name' => 'Family Member Name',
                'family_relation' => 'Relation',
                'occupation' => 'Occupation',
            ],
            'education' => [
                'qualification' => 'Qualification',
                'institution_name' => 'Name of Institution',
                'education_location' => 'Education Location',
                'education_from_date' => 'Education From Date',
                'education_to_date' => 'Education To Date',
                'specialization' => 'Specialization',
                'percentage' => 'Percentage',
            ],
            'past_employment' => [
                'past_employed_as' => 'Past Employed As',
                'past_org_name' => 'Past Organisation Name',
                'past_location' => 'Past Location',
                'past_designation' => 'Past Designation',
                'past_department' => 'Past Department',
                'past_role' => 'Past Role',
                'past_annual_ctc' => 'Past Annual CTC',
                'past_from_date' => 'Past From Date',
                'past_to_date' => 'Past To Date',
                'has_uan' => 'Has Uan',
                'uan_number' => 'Uan Number',
            ],
            'current_employment' => [
                'current_department' => 'Current Department',
                'current_designation' => 'Current Designation',
                'current_role' => 'Current Role',
                'current_location' => 'Current Location',
                'current_annual_ctc' => 'Current Annual CTC',
                'company_email' => 'Company Email',
                'jobtype' => 'Jobtype',
                'doj' => 'Date of Join',
            ],
            'references' => [
                'ref_name' => 'References Name',
                'ref_org_name' => 'References Organisation Name',
                'ref_designation' => 'References Designation',
                'ref_mobile' => 'References Mobile No',
                'ref_email' => 'References Email',
            ],
        ];

        // Default visible columns
        $defaultVisible = [
            'checkbox',
            'serial',
            'emp_id',
            'image',
            'name',
            'current_designation',
            'doj',
            'company_email',
            'personal_mobile',
            'employee_data',
            'action'
        ];

        // Get saved preferences or use defaults
        $employee = Employee::first();
        $savedPrefs = [
            'visible_columns' => $employee->column_preferences['visible_columns'] ?? $defaultVisible,
            'column_order' => $employee->column_preferences['column_order'] ?? $defaultVisible
        ];

        return view('dashboard.hr.employee.columnmanage', compact(
            'allColumns',
            'defaultVisible',
            'savedPrefs'
        ));
    }

    public function saveColumns(Request $request)
    {
        $validated = $request->validate([
            'visible_columns' => 'required|array',
            'column_order' => 'required|array',
        ]);

        $visible = $request->visible_columns;
        $order = $request->column_order;

        // Add any missing visible columns to the end of column_order
        foreach ($visible as $column) {
            if (!in_array($column, $order)) {
                $order[] = $column;
            }
        }

        foreach (Employee::all() as $employee) {
            $employee->column_preferences = [
                'visible_columns' => $visible,
                'column_order' => $order,
            ];
            $employee->save();
        }

        return redirect()->route('emplist')->with('success', 'Column preferences saved successfully!');
    }


    public function resetColumns(Request $request)
    {
        try {
            $defaultVisible = [
                'checkbox',
                'serial',
                'emp_id',
                'image',
                'name',
                'current_designation',
                'doj',
                'company_email',
                'personal_mobile',
                'employee_data',
                'action'
            ];

            Employee::query()->update([
                'column_preferences' => [
                    'visible_columns' => $defaultVisible,
                    'column_order' => $defaultVisible
                ]
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function emplist()
    {
        $personal = Employee::with([
            'families',
            'educations',
            'pastemps',
            'bloodGroupid',
            'relationshipid',
            'Designationid',
            'Departmentid',
            'Branchid',
            'Jobtypeid',
            'prorefs'
        ])->where('delete_status', 1)->orderBy('emp_id', 'desc')->get();

        // Get column preferences from the first employee (assuming all have the same preferences)
        $columnPrefs = $personal->first()->column_preferences ?? [
            'visible_columns' => [
                'checkbox',
                'serial',
                'emp_id',
                'image',
                'name',
                'current_designation',
                'doj',
                'company_email',
                'personal_mobile',
                'employee_data',
                'action',
            ],
            'column_order' => [
                'checkbox',
                'serial',
                'emp_id',
                'image',
                'name',
                'current_designation',
                'doj',
                'company_email',
                'personal_mobile',
                'employee_data',
                'action',
            ],
        ];

        // Map column keys to their display names
        $columnNames = [
            'checkbox' => 'Checkbox',
            'serial' => '#',
            'emp_id' => 'Emp ID',
            'image' => 'Picture',
            'name' => 'Name',
            'fathername' => 'FatherName',
            'address' => 'Address',
            'personal_email' => 'Personal Email',
            'personal_mobile' => 'Mobile No',
            'blood_group' => 'Blood Group',
            'gender' => 'Gender',
            'marital_status' => 'Marital Status',
            'dob' => 'Date of Birth',
            'pancard_no' => 'Pancard No',
            'aadhaar_no' => 'Aadhaar card No',
            'contact_person' => 'Contact Person Name',
            'relation' => 'Relation',
            'emergency_contact' => 'Emergency Contact',
            'family_member_name' => 'Family Member Name',
            'family_relation' => 'Relation',
            'occupation' => 'Occupation',
            'qualification' => 'Qualification',
            'institution_name' => 'Name of Institution',
            'education_location' => 'Education Location',
            'education_from_date' => 'Education From Date',
            'education_to_date' => 'Education To Date',
            'specialization' => 'Specialization',
            'percentage' => 'Percentage',
            'past_employed_as' => 'Past Employed As',
            'past_org_name' => 'Past Organisation Name',
            'past_location' => 'Past Location',
            'past_designation' => 'Past Designation',
            'past_department' => 'Past Department',
            'past_role' => 'Past Role',
            'past_annual_ctc' => 'Past Annual CTC',
            'past_from_date' => 'Past From Date',
            'past_to_date' => 'Past To Date',
            'has_uan' => 'Has Uan',
            'uan_number' => 'Uan Number',
            'current_department' => 'Current Department',
            'current_designation' => 'Designation',
            'jobtype' => 'Jobtype',

            'current_location' => 'Current Location',
            'current_annual_ctc' => 'Current Annual CTC',
            'company_email' => 'Company Email',
            'doj' => 'DoJ',
            'ref_name' => 'References Name',
            'ref_org_name' => 'References Organisation Name',
            'ref_designation' => 'References Designation',
            'ref_mobile' => 'References Mobile No',
            'ref_email' => 'References Email',
            'employee_data' => 'form status',
            'action' => 'Action',
        ];

        // Filter and order columns based on preferences
        $visibleColumns = array_filter($columnPrefs['column_order'], function ($col) use ($columnPrefs) {
            return in_array($col, $columnPrefs['visible_columns']);
        });



        return view('dashboard.hr.employee.list', [
            'personal' => $personal,
            'visibleColumns' => $visibleColumns,
            'columnNames' => $columnNames
        ]);
    }










    public function confirmedemp()
    {
        $probations = Probation::with([
            'employee',
            'departmentRelation',
            'designationRelation'
        ])
            ->where('delete_status', 1)
            ->where('appropriate_option', 1) // 🚀 Only confirmed employees
            ->get();

        $employees = Employee::with('Departmentid')->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.confirmedemp.list', compact('probations', 'employees', 'departments'));
    }

    public function confirmedshow($id)
    {
        try {
            $probation = Probation::with([
                'employee',
                'departmentRelation',
                'designationRelation'
            ])->findOrFail($id);

            // Check if this is actually a confirmed employee
            if ($probation->appropriate_option != 1) {
                return redirect()->route('confirmedemp')
                    ->with('error', 'This employee is not confirmed.');
            }

            return view('dashboard.hr.confirmedemp.show', compact('probation'));
        } catch (\Exception $e) {
            \Log::error('Error fetching confirmed employee details: ' . $e->getMessage());
            return redirect()->route('confirmedemp')
                ->with('error', 'Confirmed employee record not found or unable to load details.');
        }
    }
}

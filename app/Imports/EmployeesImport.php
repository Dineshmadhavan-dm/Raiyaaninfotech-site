<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Education;
use App\Models\Jobtype;
use App\Models\Pastemployee;
use App\Models\Professionalreference;
use App\Models\Qualification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $rowCount = 0;
    private $failedRows = [];

    public function model(array $row)
    {
        try {
            // Convert all keys to snake_case for consistency
            $row = $this->normalizeKeys($row);

            // Create employee
            $employee = Employee::create([
                'fullname' => $row['full_name'] ?? null,
                'personal_email' => $row['email_personal'] ?? null,
                'personal_mobile' => $row['personal_mobile_no'] ?? null,
                'gender' => isset($row['gender']) ? (strtolower($row['gender']) === 'male' ? 0 : 1) : null,

                // Current Employment
                'dojprovision_from_date' => $this->parseDate($row['dojprovision_from_date'] ?? null),
                'provision_to_date' => $this->parseDate($row['provision_to_date'] ?? null),
                'inmonth' => $row['inmonth'] ?? null,
                'jobtype' => $this->getJobTypeId($row['job_type'] ?? null),
                'cur_department' => $this->getDepartmentId($row['cur_department'] ?? null),
                'cur_designation' => $this->getDesignationId($row['cur_designation'] ?? null),
                'cur_location' => $this->getBranchId($row['cur_location'] ?? null),
                'cur_annual_ctc' => $this->parseCtc($row['cur_annual_ctc'] ?? null),

                'status_for_stepform' => Employee::STEP_PERSONAL
            ]);

            // Education
            if (!empty($row['qualification'])) {
                Education::create([
                    'employee_id' => $employee->emp_id,
                    'qualification' => $this->getQualificationId($row['qualification']),
                    'name_of_institution' => $row['name_of_institution'] ?? null,
                    'edu_location' => $row['edu_location'] ?? null,
                    'edu_from_date' => $this->parseDate($row['edu_from_date'] ?? null),
                    'edu_to_date' => $this->parseDate($row['edu_to_date'] ?? null),
                    'specialization' => $row['specialization'] ?? null,
                    'percentage_grade' => $row['percentage_grade'] ?? null,
                ]);
                $employee->update(['status_for_stepform' => Employee::STEP_EDUCATION]);
            }

            // Past Employment
            $employedAs = isset($row['employed_as']) ? (strtolower($row['employed_as']) === 'experienced' ? 1 : 0) : null;

            Pastemployee::create([
                'employee_id' => $employee->emp_id,
                'employed_as' => $employedAs,
                'past_organisation_name' => $row['past_organisation_name'] ?? null,
                'past_designation' => $row['past_designation'] ?? null,
                'past_annual_ctc' => $this->parseCtc($row['past_annual_ctc'] ?? null),
                'past_from_date' => $this->parseDate($row['past_from_date'] ?? null),
                'past_to_date' => $this->parseDate($row['past_to_date'] ?? null),
                'past_location' => $row['past_location'] ?? null,
                'past_department' => $row['past_department'] ?? null,
                'past_role' => $row['past_role'] ?? null,
                'has_uan' => isset($row['has_uan']) ? (strtolower($row['has_uan']) === 'yes' ? 1 : 0) : null,
                'uan_number' => $row['uan_number'] ?? null,
            ]);
            $employee->update(['status_for_stepform' => Employee::STEP_PAST_EMPLOYMENT]);

            // References
            if (!empty($row['ref_name'])) {
                Professionalreference::create([
                    'employee_id' => $employee->emp_id,
                    'ref_name' => $row['ref_name'] ?? null,
                    'ref_organization_name' => $row['ref_organization_name'] ?? null,
                    'ref_designation' => $row['ref_designation'] ?? null,
                    'mobile_no' => $row['ref_mobile_no'] ?? null,
                    'email_ref' => $row['email_ref'] ?? null,
                ]);
                $employee->update(['status_for_stepform' => Employee::STEP_REFERENCES]);
            }

            $this->rowCount++;
            return $employee;
        } catch (\Exception $e) {
            $this->failedRows[] = [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email_personal' => 'required|email|unique:employees,personal_email',
            'personal_mobile_no' => 'required|max:20',
            'gender' => 'required|in:male,female,Male,Female',

            // Education
            'qualification' => 'required|string',
            'name_of_institution' => 'required|string|max:255',
            'edu_location' => 'required|string|max:255',
            'edu_from_date' => 'required',
            'edu_to_date' => 'nullable',
            'specialization' => 'required|string|max:255',
            'percentage_grade' => 'required|numeric|between:0,100',

            // Past Employment
            'employed_as' => 'required|in:new,experienced,New,Experienced',
            'past_organisation_name' => 'required_if:employed_as,experienced|nullable|string|max:255',
            'past_designation' => 'required_if:employed_as,experienced|nullable|string|max:255',
            'past_annual_ctc' => 'required_if:employed_as,experienced|nullable|string',
            'past_from_date' => 'required_if:employed_as,experienced|nullable',
            'past_to_date' => 'required_if:employed_as,experienced|nullable',


            // Current Employment
            'dojprovision_from_date' => 'required|date_format:d-m-Y',
            'provision_to_date' => 'required|date_format:d-m-Y',

            'jobtype' => 'nullable|string',
            'cur_department' => 'required|string',
            'cur_designation' => 'required|string',
            'cur_location' => 'required|string',
            'cur_annual_ctc' => 'required|string',

            // References
            'ref_name' => 'required|string|max:255',
            'ref_organization_name' => 'required|string|max:255',
            'ref_designation' => 'required|string|max:255',
            'ref_mobile_no' => 'required|max:20',
            'email_ref' => 'required|email',
        ];
    }

    // Helper methods

    private function normalizeKeys(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[strtolower(str_replace(' ', '_', $key))] = $value;
        }
        return $normalized;
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Handle Excel timestamp values
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))
                    ->format('Y-m-d');
            }

            // Try d-m-Y format first
            if ($parsed = Carbon::createFromFormat('d-m-Y', $date)) {
                return $parsed->format('Y-m-d');
            }

            // Try other common formats
            return Carbon::createFromFormat('Y-m-d', $date) ?:
                Carbon::createFromFormat('m/d/Y', $date) ?:
                Carbon::parse($date);
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: $date", ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function parseCtc($value)
    {
        if (empty($value)) {
            return null;
        }

        // Remove commas and any non-numeric characters except decimal point
        $numericValue = (float) preg_replace('/[^0-9.]/', '', $value);

        // Format as 10,00,000 style if needed
        return number_format($numericValue, 2, '.', '');
    }

    private function getDepartmentId($name)
    {
        $department = Department::where('dep_name', $name)->first();
        return $department ? $department->dep_id : null;
    }

    private function getDesignationId($name)
    {
        $designation = Designation::where('des_name', $name)->first();
        return $designation ? $designation->des_id : null;
    }

    private function getBranchId($name)
    {
        $branch = Branch::where('branch_name', $name)->first();
        return $branch ? $branch->branch_id : null;
    }

    private function getQualificationId($name)
    {
        $qualification = Qualification::where('qua_name', $name)->first();
        return $qualification ? $qualification->qua_id : null;
    }

    private function getJobTypeId($name)
    {
        $jobType = Jobtype::where('jobtype_name', $name)->first();
        return $jobType ? $jobType->jobtype_id : null;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }
}

<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Attendance([
            'attendance_empname' => $row['employee_name'],
            'attendance_depname' => $row['department'],
            'clock_in' => Carbon::parse($row['clock_in'])->format('H:i:s'),
            'clock_out' => Carbon::parse($row['clock_out'])->format('H:i:s'),
            'attendance_loc' => $row['location'],
            'attendance_workfrom' => $row['work_from'],
            'attendance_type' => $row['attendance_type'],
            'attendancedate_no' => Carbon::createFromFormat('d-m-Y', $row['date'])->format('Y-m-d'),
            // Default values for missing fields
            'mark_attendance' => 'multiple',
            'attenddaterange_from' => null,
            'attenddaterange_to' => null,
            'month_year' => null,
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_name' => 'required|string',
            'department' => 'required|string',
            'clock_in' => 'required|date_format:h:i A',
            'clock_out' => 'required|date_format:h:i A|after:clock_in',
            'location' => 'nullable|string',
            'work_from' => 'required|in:home,office',
            'attendance_type' => 'required|in:present,late,leave,absent,dayoff,holiday,half day',
            'date' => 'required|date_format:d-m-Y',
        ];
    }
}

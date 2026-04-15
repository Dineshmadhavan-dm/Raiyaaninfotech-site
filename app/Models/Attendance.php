<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'employee_id',

        'clock_in',
        'clock_out',
        'clock_in_ip',
        'clock_out_ip',
        'attendance_loc',
        'attendance_workfrom',
        'attendance_type',

        'attendance_depname',
        'attendance_depadmin',
        'attendance_empname',
        'mark_attendance',
        'half_day_type',
        'early_clock_in_time',
        'early_clock_out_time',
        'attendancedate_no',
        'attenddaterange_from',
        'attenddaterange_to',

        'month_year',


        'delete_status'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    // In Attendance model

    public function branchid()
    {
        return $this->belongsTo(Branch::class, 'attendance_loc', 'branch_id');
    }
    // In Attendance.php model

    protected $casts = [
        'attendance_workfrom' => 'integer',
        'attendance_type' => 'integer',
        'half_day_type' => 'integer',
        'mark_attendance' => 'integer',
    ];
}
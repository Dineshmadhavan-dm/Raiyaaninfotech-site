<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';
    protected $primaryKey = 'leave_id';

    protected $fillable = [
        'employee_id',
        'attend_id',
        'member',
        'leave_type_id',
        'leave_status',
        'select_duration',
        'reason_forleave',
        'leave_file',
        'leavedate_no',
        'leavedaterange_from',
        'leavedaterange_to',
        'delete_status'
    ];

    protected $casts = [
        'leavedate_no' => 'date',
        'leavedaterange_from' => 'date',
        'leavedaterange_to' => 'date'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    public function attendances()
    {
        return $this->belongsTo(Attendance::class, 'attend_id', 'attendance_id');
    }
    public function leavetype()
    {
        return $this->belongsTo(Leavetype::class, 'leave_type_id', 'leavetype_id');
    }
}

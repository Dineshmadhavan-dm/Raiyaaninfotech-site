<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leavetype extends Model
{
    protected $primaryKey = 'leavetype_id';

    protected $table = 'leavetypes';
    protected $fillable = [
        'employee_name_id',
        'department_name_id',
        'leavetype_name_id',
        'leave_start_from',
        'leave_end_to',
        'leavetype_name',
        'leave_days',
        'delete_status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_name_id', 'emp_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_name_id', 'dep_id');
    }

    protected $appends = ['leavetype_name_text'];

    public function getLeavetypeNameTextAttribute()
    {
        $types = [
            1 => 'Privilege Leave (PL)',
            2 => 'Casual Leave (CL)',
            3 => 'Sick Leave (SL)',
            4 => 'Maternity Leave (ML)',
            5 => 'Compensatory Off (Comp-off)',
            6 => 'Marriage Leave',
            7 => 'Paternity Leave',
            8 => 'Bereavement Leave',
            9 => 'UnPaid Leave (UL)'
        ];

        return $types[$this->leavetype_name_id] ?? 'Unknown Leave Type';
    }
}

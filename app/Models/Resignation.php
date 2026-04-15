<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    protected $primaryKey = 'resignation_id';
    protected $fillable = [
        'employee_id',
        'employee_email',
        'department',
        'designation',
        'is_voluntary',
        'has_notice_period',
        'notice_start_date',
        'notice_end_date',
        'last_working_day',
        'notice_period',
        'resignation_reason',
        'reason_other_details',
        'reason_details',
        'resignation_document',
        'employee_signature',
        'can_be_rehired',
        'management_signature',
        'rehire_conditions',
        'date_of_resignation',
        'delete_status',
        'full_name',
        'date_of_hire',
        'acknowledgement',
    ];

    protected $casts = [
        'notice_start_date' => 'date',
        'notice_end_date' => 'date',
        'last_working_day' => 'date',
        'date_of_resignation' => 'date',
        'is_voluntary' => 'boolean',
        'has_notice_period' => 'boolean',
        'can_be_rehired' => 'boolean',
        'delete_status' => 'boolean',
        'acknowledgement' => 'boolean',
        'date_of_hire' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    public function departmentRelation()
    {
        return $this->belongsTo(Department::class, 'department', 'dep_id');
    }

    public function designationRelation()
    {
        return $this->belongsTo(Designation::class, 'designation', 'des_id');
    }

    public function getResignationReasonTextAttribute()
    {
        $reasons = [
            0 => 'Other',
            1 => 'Salary',
            2 => 'Better opportunity',
            3 => 'Moving to a new location',
            4 => 'Will focus on studies',
            5 => 'Not happy with the job',
            6 => 'Personal reasons',
            7 => 'Retirement'
        ];

        return $reasons[$this->resignation_reason] ?? 'Unknown';
    }
}
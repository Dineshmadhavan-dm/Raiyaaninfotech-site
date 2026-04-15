<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termination extends Model
{
    protected $primaryKey = 'termination_id';
    protected $fillable = [
        'employee_id',
        'employee_email',
        'department',
        'designation',
        'termination_type',
        'reason_for_termination',
        'supporting_documents',
        'employee_statement',
        'can_be_rehired',
        'confirm_termination',
        'rehire_conditions',
        'full_name',
        'date_of_hire',
        'acknowledgement',
        'termination_date',
        'proposed_designation',
        'employee_signature',
        'manager_signature',
        'delete_status'
    ];

    protected $casts = [
        'date_of_hire' => 'date',
        'termination_date' => 'date',
        'termination_type' => 'boolean',
        'can_be_rehired' => 'boolean',
        'confirm_termination' => 'boolean',
        'acknowledgement' => 'boolean',
    ];

    public function proposedDesignation()
    {
        return $this->belongsTo(Designation::class, 'proposed_designation', 'des_id');
    }

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
}
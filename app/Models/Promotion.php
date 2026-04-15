<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $primaryKey = 'promotion_id';
    protected $fillable = [
        'employee_id',
        'emp_email',
        'department',
        'designation',
        'proposed_designation',
        'percentage_increase',
        'supporting_documents',
        'additional_comments',
        'manager_name',
        'date_of_signature',
        'full_name',
        'date_of_hire',
        'proposed_new_salary',
        'reason_for_promotion',
        'manager_signature',
        'delete_status'
    ];

    protected $casts = [
        'date_of_signature' => 'date',
        'date_of_hire' => 'date',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    public function employeeid()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    public function departmentRelation()
    {
        return $this->belongsTo(Department::class, 'department', 'dep_id');
    }

    public function currentDesignation()
    {
        return $this->belongsTo(Designation::class, 'designation', 'des_id');
    }

    public function proposedDesignation()
    {
        return $this->belongsTo(Designation::class, 'proposed_designation', 'des_id');
    }
}

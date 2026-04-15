<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Probation extends Model
{
    protected $primaryKey = 'probation_id';
    protected $fillable = [
        'employee_id',
        'employee_email',
        'department',
        'designation',
        'supervisor_name',
        'probation_from',
        'knowledge_score',
        'skills_score',
        'quality_score',
        'productivity_score',
        'teamwork_score',
        'punctuality_score',
        'dependability_score',
        'communication_score',
        'knowledge_notes',
        'skills_notes',
        'quality_notes',
        'productivity_notes',
        'teamwork_notes',
        'punctuality_notes',
        'dependability_notes',
        'communication_notes',
        'supporting_documents',
        'overall_rating',
        'appropriate_option',
        'comments',
        'date_of_joined',
        'probation_to',
        'hr_signature',
        'date_of_evaluation',
        'delete_status',

    ];

    protected $casts = [
        'probation_from' => 'date',
        'date_of_joined' => 'date',
        'probation_to' => 'date',
        'date_of_evaluation' => 'date',
        'delete_status' => 'boolean',
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

    public function getOverallRatingTextAttribute()
    {
        $ratings = [
            1 => 'Not Satisfied',
            2 => 'Somewhat Satisfied',
            3 => 'Satisfied'
        ];

        return $ratings[$this->overall_rating] ?? 'Not Rated';
    }

    public function getAppropriateOptionTextAttribute()
    {
        $options = [
            1 => 'Confirmed',
            2 => 'Extended',
            3 => 'Terminated'
        ];

        return $options[$this->appropriate_option] ?? 'Not Decided';
    }
}
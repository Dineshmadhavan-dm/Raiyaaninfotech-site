<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';
    protected $primaryKey = 'edu_id';
    protected $fillable = [
        'employee_id',
        'qualification',
        'name_of_institution',
        'edu_location',
        'edu_from_date',
        'edu_to_date',
        'specialization',
        'percentage_grade',
        'delete_status'


    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    public function Qualificationid()
    {
        return $this->belongsTo(Qualification::class, 'qualification', 'qua_id');
    }
}

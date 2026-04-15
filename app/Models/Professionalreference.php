<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professionalreference extends Model
{
    protected $table = 'professionalreferences';
    protected $primaryKey = 'ref_id';

    protected $fillable = [
        'employee_id',
        'ref_name',
        'ref_organization_name',
        'ref_designation',
        'mobile_no',
        'email_ref',
        'delete_status'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}

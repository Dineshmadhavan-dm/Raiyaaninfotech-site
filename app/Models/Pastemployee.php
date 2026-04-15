<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pastemployee extends Model
{

    protected $table = 'pastemployees';
    protected $primaryKey = 'pastemp_id';

    protected $fillable = [
        'employee_id',
        'employed_as',

        'past_organisation_name',
        'past_location',
        'past_department',
        'past_designation',
        'past_role',
        'past_annual_ctc',
        'past_from_date',
        'past_to_date',
        'has_uan',
        'uan_number',
        'delete_status'
    ];


    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}

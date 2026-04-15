<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'families';
    protected $primaryKey = 'family_id';

    protected $fillable = [
        'employee_id',
        'fa_name',
        'fa_relation',
        'fa_occupation',
        'delete_status'
    ];


    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
    public function relationShipid()
    {
        return $this->belongsTo(Relationship::class, 'fa_relation', 'relationship_id');
    }
}

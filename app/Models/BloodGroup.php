<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodGroup extends Model
{

    protected $table = 'blood_groups';
    protected $primaryKey = 'bloodgroup_id';

    protected $fillable = [
        'bloodgroup_name',
        'delete_status'
    ];
}

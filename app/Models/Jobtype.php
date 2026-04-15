<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobtype extends Model
{
    protected $table = 'jobtypes';
    protected $primaryKey = 'jobtype_id';

    protected $fillable = [
        'jobtype_name',
        'delete_status'
    ];
}

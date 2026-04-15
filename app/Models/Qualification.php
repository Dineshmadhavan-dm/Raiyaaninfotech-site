<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $table = 'qualifications';
    protected $primaryKey = 'qua_id';

    protected $fillable = [
        'qua_name',
        'delete_status'
    ];
}

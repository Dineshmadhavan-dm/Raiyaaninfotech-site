<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidaytype extends Model
{
    protected $table = 'holidaytypes';
    protected $primaryKey = 'holidaytype_id';

    protected $fillable = [
        'holidaytype_name',
        'delete_status'
    ];
}
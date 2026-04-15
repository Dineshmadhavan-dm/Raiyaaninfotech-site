<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    protected $primaryKey = 'site_id';

    protected $fillable = ['site_id', 'page_name', 'status'];
}

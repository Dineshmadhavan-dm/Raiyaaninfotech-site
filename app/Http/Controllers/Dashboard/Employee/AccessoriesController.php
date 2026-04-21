<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessoriesController extends Controller
{
    public function index(){
return view('dashboard.employee.accessories.index');
    }
}

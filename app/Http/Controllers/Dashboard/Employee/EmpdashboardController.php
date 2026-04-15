<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpdashboardController extends Controller
{

    public function emphome()
    {
        return view('dashboard.employee.empdashboard.index');
    }
}

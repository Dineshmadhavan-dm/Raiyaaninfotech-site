<?php

use App\Http\Controllers\Dashboard\Employee\EmpholidayController;
use Illuminate\Support\Facades\Route;

Route::get('holidays', [EmpholidayController::class, 'index'])->name('empholidaylist');
Route::get('holidays/list', [EmpholidayController::class, 'getHolidays'])->name('empholidayget');

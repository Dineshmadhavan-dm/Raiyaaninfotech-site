<?php

use App\Http\Controllers\Dashboard\Employee\ApplyleaveController;

use App\Http\Controllers\Dashboard\HR\ShiftController;
use Illuminate\Support\Facades\Route;


Route::get('leaves', [ApplyleaveController::class, 'empleaveindex'])->name('empleaveindex');
Route::get('leave/data', [ApplyleaveController::class, 'getLeaveData'])->name('leave.data');
Route::post('leave/data', [ApplyleaveController::class, 'getLeaveData']);
Route::post('leave/stores', [ApplyleaveController::class, 'store'])->name('leave.store');
Route::get('leave/edits/{id}', [ApplyleaveController::class, 'edit'])->name('leave.edit');
Route::post('leave/updates/{id}', [ApplyleaveController::class, 'update'])->name('leave.update');
Route::get('leave/show/{id}', [ApplyleaveController::class, 'show'])->name('leave.show');
Route::get('leave/related/{id}', [ApplyleaveController::class, 'getRelatedLeaves'])->name('leave.related');

Route::delete('leave/delete/{id}', [ApplyleaveController::class, 'destroy'])->name('leave.delete');
Route::get('shift/check', [ApplyleaveController::class, 'checkShift'])->name('shift.check');

// New routes for duplicate checking and day-off handling
Route::post('leave/check-duplicates', [ApplyleaveController::class, 'checkDuplicateLeaves'])->name('leave.check-duplicates');
Route::get('leave/types/{employeeId}', [ApplyleaveController::class, 'getLeaveTypes'])->name('leave.types');

Route::get('holidays/employee', [ApplyleaveController::class, 'getEmployeeHolidays'])->name('holidays.employee');
Route::get('holidays/checks', [ApplyleaveController::class, 'checkHoliday'])->name('holidays.check');
Route::get('leave/dates/{id}', [ApplyleaveController::class, 'getLeaveDates'])->name('leave.dates');

Route::get('shift/check-dayoffs', [ApplyleaveController::class, 'checkDayOff'])->name('shift.check-dayoffs');
Route::get('shift/check-dayoffs-range', [ApplyleaveController::class, 'checkDayoffsInRange'])->name('shift.check-dayoffs-range');

Route::post('leave/reject/{id}', [ApplyleaveController::class, 'rejectLeave'])->name('leave.reject');


Route::get('leave/check-unavailable-dates', [ApplyleaveController::class, 'checkUnavailableDates'])->name('leave.check-unavailable-dates');
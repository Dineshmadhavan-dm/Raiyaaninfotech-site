<?php

use App\Http\Controllers\Dashboard\HR\LeaveController;
use App\Http\Controllers\Dashboard\HR\LeaveTypeController;
use App\Http\Controllers\Dashboard\HR\ShiftController;
use Illuminate\Support\Facades\Route;


Route::get('leave', [LeaveController::class, 'leaveindex'])->name('leaveindex');
Route::get('leave/data', [LeaveController::class, 'getLeaveData'])->name('leave.data');
Route::post('leave/data', [LeaveController::class, 'getLeaveData']);
Route::post('leave/store', [LeaveController::class, 'store'])->name('leave.store');
Route::get('leave/edit/{id}', [LeaveController::class, 'edit'])->name('leave.edit');
Route::post('leave/update/{id}', [LeaveController::class, 'update'])->name('leave.update');
Route::get('leave/show/{id}', [LeaveController::class, 'show'])->name('leave.show');
Route::get('leave/related/{id}', [LeaveController::class, 'getRelatedLeaves'])->name('leave.related');
Route::post('leave/approve-all', [LeaveController::class, 'approveAll'])->name('leave.approve-all');
Route::post('leave/reject-all', [LeaveController::class, 'rejectAll'])->name('leave.reject-all');
Route::delete('leave/delete/{id}', [LeaveController::class, 'destroy'])->name('leave.delete');
Route::get('shift/check', [ShiftController::class, 'checkShift'])->name('shift.check');

Route::post('leave/check-existing', [LeaveController::class, 'checkExistingLeaves'])->name('leave.check-existing');

Route::get('leave/types/{employeeId}', [LeaveController::class, 'getLeaveTypesByEmployee']);

//fetch  route
Route::get('holidays/employee', [leaveController::class, 'getEmployeeHolidays'])->name('holidays.employee');
Route::get('holidays/check', [leaveController::class, 'checkHoliday'])->name('holidays.check');
Route::get('leave/dates/{id}', [LeaveController::class, 'getLeaveDates'])->name('leave.dates');

Route::get('shift/check-dayoff', [ShiftController::class, 'checkDayOff']);


// In web.php
Route::post('leave/approve/{id}', [LeaveController::class, 'approveLeave'])->name('leave.approve');
Route::post('leave/reject/{id}', [LeaveController::class, 'rejectLeave'])->name('leave.reject');
// Add this route for marking attendance as present from absent
Route::post('/leave/mark-present-from-absent', [LeaveController::class, 'markPresentFromAbsent'])
    ->name('attendance.mark.present.from.absent');
    Route::post('/leave/update-type/{id}', [LeaveController::class, 'updateLeaveType'])->name('leave.updateType');


Route::prefix('leavetype')->group(function () {

    Route::get('/', [LeaveTypeController::class, 'leavetypeindex'])->name('leavetypeindex');
    Route::post('/', [LeaveTypeController::class, 'store'])->name('leavetypes.store');
    Route::get('/get', [LeaveTypeController::class, 'getLeaveTypes'])->name('leavetypes.get');
    Route::put('/{employeeId}', [LeaveTypeController::class, 'update'])->name('leavetypes.update');
    Route::delete('/{id}', [LeaveTypeController::class, 'destroy'])->name('leavetypes.destroy');
    Route::get('/{id}/edit', [LeaveTypeController::class, 'edit'])->name('leavetypes.edit'); // Add this line


    Route::post('leavetype/check-existing', [LeaveTypeController::class, 'checkExisting'])->name('leavetype.check-existing');



    Route::get('/manage-columns', [LeaveTypeController::class, 'manageColumns'])->name('leavetype.manage.columns');
    Route::post('/save-columns', [LeaveTypeController::class, 'saveColumns'])->name('save.leavetype.columns');
    Route::post('/reset-columns', [LeaveTypeController::class, 'resetColumns'])->name('reset.leavetype.columns');
});

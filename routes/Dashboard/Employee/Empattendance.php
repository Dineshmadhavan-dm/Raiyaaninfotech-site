<?php

use App\Http\Controllers\Dashboard\Employee\EmpAttendanceController;
use Illuminate\Support\Facades\Route;



Route::get('attendances', [EmpAttendanceController::class, 'empattendlist'])->name('empattendancelist');
Route::get('attendances/get', [EmpAttendanceController::class, 'getAttendances'])->name('attendance.get');
Route::post('attendances/create', [EmpAttendanceController::class, 'attend_create'])->name('attendance.create');
Route::post('attendances/bulk-create', [EmpAttendanceController::class, 'attend_bulkCreate'])->name('attendance.bulk-create');
Route::put('attendances/update', [EmpAttendanceController::class, 'attend_update'])->name('attendance.update');
Route::delete('attendances/delete/{id}', [EmpAttendanceController::class, 'attend_delete'])->name('attendance.delete');

Route::get('attendances/export-template', [EmpAttendanceController::class, 'downloadTemplate'])->name('attendances.exportTemplate');
Route::get('attendances/export', [EmpAttendanceController::class, 'export'])->name('attendances.export');
Route::post('attendances/import', [EmpAttendanceController::class, 'import'])->name('attendances.import');

<?php

use App\Http\Controllers\Dashboard\HR\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('attendance', [AttendanceController::class, 'attendlist'])->name('attendancelist');
Route::get('attendances/get', [AttendanceController::class, 'getAttendances'])->name('attendance.get');
Route::post('attendances/create', [AttendanceController::class, 'attend_create'])->name('attendance.create');
Route::post('attendances/bulk-create', [AttendanceController::class, 'attend_bulkCreate'])->name('attendance.bulk-create');
Route::put('attendances/update', [AttendanceController::class, 'attend_update'])->name('attendance.update');
Route::delete('attendances/delete/{id}', [AttendanceController::class, 'attend_delete'])->name('attendance.delete');

Route::get('attendances/export-template', [AttendanceController::class, 'downloadTemplate'])->name('attendances.exportTemplate');
Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import');

Route::get('attendances/export-pdf', [AttendanceController::class, 'exportPdf'])->name('attendances.export-pdf');

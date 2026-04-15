<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\HR\ShiftController;

Route::get('shift-planner', [ShiftController::class, 'shiftindex'])->name('shiftindex');
Route::get('shifts/get', [ShiftController::class, 'getShifts'])->name('shifts.get');
Route::post('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
Route::put('shifts/update', [ShiftController::class, 'update'])->name('shifts.update');
Route::post('shifts/bulk-create', [ShiftController::class, 'bulkCreate'])->name('shifts.bulk-create');
Route::delete('shifts/delete/{id}', [ShiftController::class, 'delete'])->name('shifts.delete');
Route::get('/shifts/upcoming-holidays', [ShiftController::class, 'upcomingHolidays']);

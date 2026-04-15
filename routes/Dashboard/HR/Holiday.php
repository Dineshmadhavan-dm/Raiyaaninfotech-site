<?php

use App\Http\Controllers\Dashboard\HR\HolidayController;
use Illuminate\Support\Facades\Route;

Route::get('holiday', [HolidayController::class, 'index'])->name('holidaylist');
Route::post('holiday', [HolidayController::class, 'store'])->name('holidaystore');
Route::post('holiday/multiple', [HolidayController::class, 'storeMultiple'])->name('holidaymultiple');
Route::get('holiday/list', [HolidayController::class, 'getHolidays'])->name('holidayget');
Route::delete('holiday/{id}', [HolidayController::class, 'destroy'])->name('holidaydestroy');
Route::put('holiday/{id}', [HolidayController::class, 'update'])->name('holiday.update');

<?php

use App\Http\Controllers\Dashboard\HR\Other\HolidaytypeController;
use Illuminate\Support\Facades\Route;

Route::get('holidaytype', [HolidaytypeController::class, 'holidaytype'])->name('holidaytype');
Route::post('holidaytype', [HolidaytypeController::class, 'holidaytypepost'])->name('holidaytypepost');
Route::get('holidaytype/edit/{holidaytype_id}', [HolidaytypeController::class, 'holidaytypeedit'])->name('holidaytypeedit');
Route::put('holidaytype/update/{holidaytype_id}', [HolidaytypeController::class, 'holidaytypeupdate'])->name('holidaytypeupdate');
Route::delete('holidaytype/delete/{holidaytype_id}', [HolidaytypeController::class, 'holidaytypedelete'])->name('holidaytypedelete');
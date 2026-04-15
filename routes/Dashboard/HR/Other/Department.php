<?php

use App\Http\Controllers\Dashboard\HR\Other\DepartmentController;
use Illuminate\Support\Facades\Route;



//department route
Route::get('department', [DepartmentController::class, 'department'])->name('department');
Route::post('department', [DepartmentController::class, 'departmentpost'])->name('departmentpost');
Route::get('department/edit/{dep_id}', [DepartmentController::class, 'departmentedit'])->name('departmentedit');
Route::put('department/update/{dep_id}', [DepartmentController::class, 'departmentupdate'])->name('departmentupdate');
Route::delete('department/delete/{dep_id}', [DepartmentController::class, 'departmentdelete'])->name('departmentdelete');

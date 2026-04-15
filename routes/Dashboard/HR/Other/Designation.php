<?php

use App\Http\Controllers\Dashboard\HR\Other\DesignationController;
use Illuminate\Support\Facades\Route;


//designation route
Route::get('designation', [DesignationController::class, 'designation'])->name('designation');
Route::post('designation', [DesignationController::class, 'designationpost'])->name('designationpost');
Route::get('designation/edit/{des_id}', [DesignationController::class, 'designationedit'])->name('designationedit');
Route::put('designation/update/{des_id}', [DesignationController::class, 'designationupdate'])->name('designationupdate');
Route::delete('designation/delete/{des_id}', [DesignationController::class, 'designationdelete'])->name('designationdelete');

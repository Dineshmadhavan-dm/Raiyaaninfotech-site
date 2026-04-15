<?php

use App\Http\Controllers\Dashboard\HR\Other\QualificationController;
use Illuminate\Support\Facades\Route;


Route::get('qualification', [QualificationController::class, 'qualification'])->name('qualification');
Route::post('qualification', [QualificationController::class, 'qualificationpost'])->name('qualificationpost');
Route::get('qualification/edit/{qua_id}', [QualificationController::class, 'qualificationedit'])->name('qualificationedit');
Route::put('qualification/update/{qua_id}', [QualificationController::class, 'qualificationupdate'])->name('qualificationupdate');
Route::delete('qualification/delete/{qua_id}', [QualificationController::class, 'qualificationdelete'])->name('qualificationdelete');

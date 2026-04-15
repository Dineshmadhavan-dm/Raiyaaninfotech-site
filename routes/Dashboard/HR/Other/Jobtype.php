<?php

use App\Http\Controllers\Dashboard\HR\Other\JobtypeController;
use Illuminate\Support\Facades\Route;

Route::get('jobtype', [JobtypeController::class, 'jobtype'])->name('jobtype');
Route::post('jobtype', [JobtypeController::class, 'jobtypepost'])->name('jobtypepost');
Route::get('jobtype/edit/{jobtype_id}', [JobtypeController::class, 'jobtypeedit'])->name('jobtypeedit');
Route::put('jobtype/update/{jobtype_id}', [JobtypeController::class, 'jobtypeupdate'])->name('jobtypeupdate');
Route::delete('jobtype/delete/{jobtype_id}', [JobtypeController::class, 'jobtypedelete'])->name('jobtypedelete');

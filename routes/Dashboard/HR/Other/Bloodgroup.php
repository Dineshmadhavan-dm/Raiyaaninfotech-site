<?php

use App\Http\Controllers\Dashboard\HR\Other\BloodgroupController;
use Illuminate\Support\Facades\Route;

Route::get('bloodgroup', [BloodgroupController::class, 'bloodgroup'])->name('bloodgroup');
Route::post('bloodgroup', [BloodgroupController::class, 'bloodgrouppost'])->name('bloodgrouppost');
Route::get('bloodgroup/edit/{bloodgroup_id}', [BloodgroupController::class, 'bloodgroupedit'])->name('bloodgroupedit');
Route::put('bloodgroup/update/{bloodgroup_id}', [BloodgroupController::class, 'bloodgroupupdate'])->name('bloodgroupupdate');
Route::delete('bloodgroup/delete/{bloodgroup_id}', [BloodgroupController::class, 'bloodgroupdelete'])->name('bloodgroupdelete');

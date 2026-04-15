<?php

use App\Http\Controllers\Dashboard\HR\Other\BranchController;
use Illuminate\Support\Facades\Route;


//branch route
Route::get('branch', [BranchController::class, 'branch'])->name('branch');
Route::post('branch', [BranchController::class, 'branchpost'])->name('branchpost');
Route::get('branch/edit/{branch_id}', [BranchController::class, 'branchedit'])->name('branchedit');
Route::put('branch/update/{branch_id}', [BranchController::class, 'branchupdate'])->name('branchupdate');
Route::delete('branch/delete/{branch_id}', [BranchController::class, 'branchdelete'])->name('branchdelete');

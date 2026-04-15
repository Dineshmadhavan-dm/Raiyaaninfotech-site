<?php

use App\Http\Controllers\Dashboard\HR\ResignationController;
use Illuminate\Support\Facades\Route;

Route::prefix('resignation')->group(function () {
    Route::get('/', [ResignationController::class, 'resignationlist'])->name('resignationlist');
    Route::get('/create', [ResignationController::class, 'resignationcreate'])->name('resignationcreate');
    Route::post('/create', [ResignationController::class, 'resignationstore'])->name('resignationstore');
    Route::get('/search', [ResignationController::class, 'search'])->name('resignation.search');
    Route::get('/{resignation_id}/edit', [ResignationController::class, 'edit'])->name('resignation.edit');
    Route::put('/{resignation_id}/update', [ResignationController::class, 'update'])->name('resignation.update');
    Route::delete('/delete/{resignation_id}', [ResignationController::class, 'destroy'])->name('resignation.destroy');
    Route::get('/{resignation_id}', [ResignationController::class, 'show'])->name('resignation.show');
});

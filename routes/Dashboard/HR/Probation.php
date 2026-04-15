<?php

use App\Http\Controllers\Dashboard\HR\ProbationController;
use Illuminate\Support\Facades\Route;

Route::prefix('probation')->group(function () {
    Route::get('/', [ProbationController::class, 'probationlist'])->name('probationlist');
    Route::get('/create', [ProbationController::class, 'probationcreate'])->name('probationcreate');
    Route::post('/create', [ProbationController::class, 'probationstore'])->name('probationstore');
    Route::get('/search', [ProbationController::class, 'search'])->name('probation.search');
    Route::get('/{probation_id}/edit', [ProbationController::class, 'edit'])->name('probation.edit');
    Route::put('/{probation_id}/update', [ProbationController::class, 'update'])->name('probation.update');
    Route::delete('/delete/{probation_id}', [ProbationController::class, 'destroy'])->name('probation.destroy');
    Route::get('/{probation_id}', [ProbationController::class, 'show'])->name('probation.show');
});
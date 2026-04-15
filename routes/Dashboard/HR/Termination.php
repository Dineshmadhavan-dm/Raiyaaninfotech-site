<?php

use App\Http\Controllers\Dashboard\HR\TerminationController;

use Illuminate\Support\Facades\Route;

Route::prefix('termination')->group(function () {
    Route::get('/', [TerminationController::class, 'terminationlist'])->name('terminationlist');
    Route::get('/create', [TerminationController::class, 'terminationcreate'])->name('terminationcreate');
    Route::post('/create', [TerminationController::class, 'terminationstore'])->name('terminationstore');
    Route::get('/search', [TerminationController::class, 'search'])->name('termination.search');
    Route::get('/{termination_id}/edit', [TerminationController::class, 'edit'])->name('termination.edit');
    Route::put('/{termination_id}/update', [TerminationController::class, 'update'])->name('termination.update');
    Route::delete('/delete/{termination_id}', [TerminationController::class, 'destroy'])->name('termination.destroy');
    Route::get('/{termination_id}', [TerminationController::class, 'show'])->name('termination.show');
});

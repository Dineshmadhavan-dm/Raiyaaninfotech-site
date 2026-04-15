<?php

use App\Http\Controllers\Dashboard\HR\HandoverController;
use Illuminate\Support\Facades\Route;

Route::prefix('handover')->group(function () {
    Route::get('/', [HandoverController::class, 'handoverlist'])->name('handoverlist');
    Route::get('/create', [HandoverController::class, 'handovercreate'])->name('handovercreate');
    Route::post('/create', [HandoverController::class, 'handoverstore'])->name('handoverstore');
    Route::get('/search', [HandoverController::class, 'search'])->name('handover.search');
    Route::get('/{handover_id}/edit', [HandoverController::class, 'edit'])->name('handover.edit');
    Route::put('/{handover_id}/update', [HandoverController::class, 'update'])->name('handover.update');
    Route::delete('/delete/{handover_id}', [HandoverController::class, 'destroy'])->name('handover.destroy');
    Route::get('/{handover_id}', [HandoverController::class, 'show'])->name('handover.show');
});

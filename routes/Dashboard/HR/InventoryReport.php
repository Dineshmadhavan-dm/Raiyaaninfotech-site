<?php

use App\Http\Controllers\Dashboard\HR\ReportController;
use Illuminate\Support\Facades\Route;

// Report Management Routes
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/export-items', [ReportController::class, 'exportItems'])->name('export-items');
    Route::get('/export-assignments', [ReportController::class, 'exportAssignments'])->name('export-assignments');
    Route::get('/export-maintenances', [ReportController::class, 'exportMaintenances'])->name('export-maintenances');
    Route::get('/export-categories', [ReportController::class, 'exportCategories'])->name('export-categories');
});

<?php

use App\Http\Controllers\Dashboard\HR\InventoryMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-maintenance')->group(function () {

    Route::get('/', [InventoryMaintenanceController::class, 'index'])->name('inventory.maintenance.index');

    Route::get('/create', [InventoryMaintenanceController::class, 'create'])->name('inventory.maintenance.create');

    Route::post('/', [InventoryMaintenanceController::class, 'store'])->name('inventory.maintenance.store');

    Route::get('/{id}', [InventoryMaintenanceController::class, 'show'])->name('inventory.maintenance.show');

    // 🔥 COMPLETE ACTION
    Route::post('/{id}/complete', [InventoryMaintenanceController::class, 'complete'])
        ->name('inventory.maintenance.complete');
});

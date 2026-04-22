<?php

use App\Http\Controllers\Dashboard\HR\InventoryMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-maintenance')->group(function () {
  // Main routes
    Route::get('/', [InventoryMaintenanceController::class, 'index'])->name('inventory.maintenance.index');
    Route::get('/create', [InventoryMaintenanceController::class, 'create'])->name('inventory.maintenance.create');
    Route::post('/', [InventoryMaintenanceController::class, 'store'])->name('inventory.maintenance.store');

    // Edit & Update routes
    Route::get('/{id}/edit', [InventoryMaintenanceController::class, 'edit'])->name('inventory.maintenance.edit');
    Route::put('/{id}', [InventoryMaintenanceController::class, 'update'])->name('inventory.maintenance.update');

    // View & Actions
    Route::get('/{id}', [InventoryMaintenanceController::class, 'show'])->name('inventory.maintenance.show');

    // AJAX routes for checking assignment
    Route::get('/check-assignment/{itemId}', [InventoryMaintenanceController::class, 'checkItemAssignment'])
        ->name('inventory.maintenance.check-assignment');

});

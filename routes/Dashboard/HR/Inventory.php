<?php

use App\Http\Controllers\Dashboard\HR\InventoryItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->group(function () {

    Route::get('/', [InventoryItemController::class, 'index'])->name('inventory.index');

    Route::get('/create', [InventoryItemController::class, 'create'])->name('inventory.create');

    Route::post('/', [InventoryItemController::class, 'store'])->name('inventory.store');

    Route::get('/{id}/edit', [InventoryItemController::class, 'edit'])->name('inventory.edit');

    Route::put('/{id}', [InventoryItemController::class, 'update'])->name('inventory.update');

    Route::delete('/{id}', [InventoryItemController::class, 'destroy'])->name('inventory.destroy');

    Route::get('/{id}', [InventoryItemController::class, 'show'])->name('inventory.show');

});

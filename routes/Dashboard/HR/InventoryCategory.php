<?php

use App\Http\Controllers\Dashboard\HR\InventoryCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-categories')->group(function () {

    Route::get('/', [InventoryCategoryController::class, 'index'])->name('inventory.categories.index');

    Route::get('/create', [InventoryCategoryController::class, 'create'])->name('inventory.categories.create');

    Route::post('/', [InventoryCategoryController::class, 'store'])->name('inventory.categories.store');

    Route::get('/{id}/edit', [InventoryCategoryController::class, 'edit'])->name('inventory.categories.edit');

    Route::put('/{id}', [InventoryCategoryController::class, 'update'])->name('inventory.categories.update');

    Route::delete('/{id}', [InventoryCategoryController::class, 'destroy'])->name('inventory.categories.destroy');

    Route::get('/{id}', [InventoryCategoryController::class, 'show'])->name('inventory.categories.show');
Route::post('/check-category', [InventoryCategoryController::class, 'checkCategory'])
    ->name('inventory.categories.check');
});

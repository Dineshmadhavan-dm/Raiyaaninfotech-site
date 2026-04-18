<?php

use App\Http\Controllers\Dashboard\HR\InventoryHistoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-history')->group(function () {

    Route::get('/', [InventoryHistoryController::class, 'index'])
        ->name('inventory.history.index');

    Route::get('/{id}', [InventoryHistoryController::class, 'show'])
        ->name('inventory.history.show');
});

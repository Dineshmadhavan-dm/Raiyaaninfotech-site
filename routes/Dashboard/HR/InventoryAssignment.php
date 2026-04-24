<?php

use App\Http\Controllers\Dashboard\HR\InventoryAssignmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-assignments')->group(function () {

    Route::get('/', [InventoryAssignmentController::class, 'index'])->name('inventory.assignments.index');

    Route::get('/create', [InventoryAssignmentController::class, 'create'])->name('inventory.assignments.create');

    Route::post('/', [InventoryAssignmentController::class, 'store'])->name('inventory.assignments.store');




    Route::get('/{id}', [InventoryAssignmentController::class, 'show'])->name('inventory.assignments.show');

    // 🔥 SPECIAL ROUTE (RETURN)
    Route::post('/{id}/return', [InventoryAssignmentController::class, 'returnItem'])
        ->name('inventory.assignments.return');


        Route::get('/get-employees/{dep_id}', [InventoryAssignmentController::class,'getEmployees'])
    ->name('inventory.assignments.getEmployees');

    Route::get('/check-item/{id}', [InventoryAssignmentController::class,'checkItem']);


});

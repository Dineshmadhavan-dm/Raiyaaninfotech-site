<?php

use App\Http\Controllers\Dashboard\Employee\AccessoriesController;

use Illuminate\Support\Facades\Route;




Route::get('accessories', [AccessoriesController::class, 'index'])->name('accessories.index');
Route::post('maintenance/request', [AccessoriesController::class, 'requestMaintenance'])->name('employee.maintenance.request');
Route::get('maintenance/check/{itemId}', [AccessoriesController::class, 'checkMaintenance'])->name('employee.maintenance.check');


  Route::post('maintenance/request', [AccessoriesController::class, 'requestMaintenance'])
        ->name('employee.maintenance.request');

    Route::get('maintenance/history', [AccessoriesController::class, 'maintenanceHistory'])
        ->name('employee.maintenance.history');

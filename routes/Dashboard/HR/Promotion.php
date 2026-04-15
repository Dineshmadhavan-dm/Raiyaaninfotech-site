<?php

use App\Http\Controllers\Dashboard\HR\PromotionController;

use Illuminate\Support\Facades\Route;

Route::prefix('promotion')->group(function () {
    Route::get('/', [PromotionController::class, 'prolist'])->name('prolist');
    Route::get('/create', [PromotionController::class, 'procreate'])->name('procreate');
    Route::post('/create', [PromotionController::class, 'prostore'])->name('prostore');
    Route::get('/search', [PromotionController::class, 'search'])->name('employees.search');
    Route::get('/{promotion_id}/edit', [PromotionController::class, 'proedit'])->name('proedit');
    Route::put('/{promotion_id}/update', [PromotionController::class, 'proupdate'])->name('proupdate');
    Route::get('/searchdesignations', [PromotionController::class, 'searchdesignation'])->name('designations.list');

    Route::delete('/delete/{promotion_id}', [PromotionController::class, 'destroy'])
        ->name('promotion.destroy');
    Route::get('/{promotion_id}', [PromotionController::class, 'proshow'])->name('proshow');
});

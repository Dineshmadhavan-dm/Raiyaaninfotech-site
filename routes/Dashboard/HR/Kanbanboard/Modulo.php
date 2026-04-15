<?php


use App\Http\Controllers\Dashboard\HR\Kanbanboard\ModuloController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'modulo'], function () {



    Route::get('', [ModuloController::class, 'modulolist'])->name('modulolist');
    Route::get('/create/{projectId?}', [ModuloController::class, 'modulocreate'])->name('modulocreate');
    Route::post('/store', [ModuloController::class, 'modulostore'])->name('modulostore');
    Route::get('/edit/{id}', [ModuloController::class, 'moduloedit'])->name('moduloedit');
    Route::put('/update/{id}', [ModuloController::class, 'moduloupdate'])->name('moduloupdate');
    Route::delete('/delete/{id}', [ModuloController::class, 'modulodestroy'])->name('modulodestroy');
    Route::get('/details/{id}', [ModuloController::class, 'getModuloDetails'])->name('modulo.details');


    Route::post('/pmts-images', [ModuloController::class, 'getPmtsImageDetails'])->name('modulo.pmts-images');
    Route::post('/update-status/{id}', [ModuloController::class, 'updateStatus'])->name('modulo.update-status');
});

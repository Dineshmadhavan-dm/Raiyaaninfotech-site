<?php

use App\Http\Controllers\Dashboard\Employee\AccessoriesController;

use Illuminate\Support\Facades\Route;


Route::get('accessories', [AccessoriesController::class, 'index'])->name('accessories.index');

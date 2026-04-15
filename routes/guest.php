<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'home'])->name('index');


Route::get('login', [LoginController::class, 'loginget'])->name('loginget');
Route::post('login', [LoginController::class, 'loginpost'])->name('loginpost');
Route::get('forget-password', [LoginController::class, 'fpwd'])->name('fpwd');

Route::post('/check-credentials', [LoginController::class, 'checkEmail'])->name('check.email');

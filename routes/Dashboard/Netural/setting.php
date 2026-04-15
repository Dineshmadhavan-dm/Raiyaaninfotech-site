<?php

use App\Http\Controllers\Dashboard\Netural\SettingController;
use Illuminate\Support\Facades\Route;



Route::post('/toggle-status', [SettingController::class, 'toggleStatus'])->name('toggle.site.status');
Route::get('applogo', [SettingController::class, 'applogo'])->name('applogo');
Route::patch('applogo', [SettingController::class, 'applogo_post'])->name('applogo_post');
Route::get('cookies', [SettingController::class, 'cookies'])->name('cookies');
Route::post('/clear-cookies', [SettingController::class, 'clearCookies'])->name('clear.cookies');
Route::get('sitecontrol', [SettingController::class, 'sitecontrol'])->name('sitecontrol');
Route::get('favicon', [SettingController::class, 'favicon'])->name('favicon');
Route::patch('favlogo', [SettingController::class, 'favlogo_post'])->name('favlogo_post');
Route::get('theme', [SettingController::class, 'theme'])->name('theme');
Route::post('theme', [SettingController::class, 'theme_post'])->name('theme_post');
Route::post('/theme/reset', [SettingController::class, 'theme_reset'])->name('theme.reset');

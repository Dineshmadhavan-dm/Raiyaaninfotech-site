<?php

use App\Http\Controllers\Dashboard\HR\PermissionController;
use App\Http\Controllers\Dashboard\HR\RoleController;
use Illuminate\Support\Facades\Route;

Route::resource('permissions', PermissionController::class);

Route::resource('roles', RoleController::class);
Route::get('roles/{id}/give-permission', [RoleController::class, 'addpermission'])->name('addpermission');
Route::put('roles/{id}/give-permission', [RoleController::class, 'givepermission'])->name('givepermission');

<?php

use App\Http\Controllers\Dashboard\HR\Kanbanboard\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('kanbanboard', [ProjectController::class, 'kanbandashboard'])->name('kanbandashboard');

Route::group(['prefix' => 'project'], function () {
    Route::get('', [ProjectController::class, 'projectlist'])->name('projectlist');
    Route::get('create', [ProjectController::class, 'projectcreate'])->name('projectcreate');
    Route::post('create', [ProjectController::class, 'projectstore'])->name('projectstore');
    Route::get('search', [ProjectController::class, 'searchEmployees'])->name('project.search');
    Route::get('{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::put('{id}/update', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('delete/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

    Route::get('{id}/details', [ProjectController::class, 'showDetails'])->name('project.details');

    Route::post('search-member-names', [ProjectController::class, 'getMemberNames'])->name('project.search-member-names');
    Route::post('pmts-images', [ProjectController::class, 'getPmtsImageDetails'])->name('project.pmts-images');
});

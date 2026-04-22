<?php

use App\Http\Controllers\Dashboard\HR\Kanbanboard\ChattaskController;
use App\Http\Controllers\Dashboard\HR\Kanbanboard\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'task'], function () {

    Route::get('', [TaskController::class, 'tasklist'])->name('tasklist');
    Route::get('/create/{moduloId?}', [TaskController::class, 'taskcreate'])->name('taskcreate');
    Route::post('/store', [TaskController::class, 'taskstore'])->name('taskstore');
    Route::get('/edit/{id}', [TaskController::class, 'taskedit'])->name('taskedit');
    Route::put('/update/{id}', [TaskController::class, 'taskupdate'])->name('taskupdate');
    Route::delete('/delete/{id}', [TaskController::class, 'taskdestroy'])->name('taskdestroy');
    Route::get('/details/{id}', [TaskController::class, 'getTaskDetails'])->name('task.details');


Route::post('/pmts-images', [TaskController::class, 'getPmtsImageDetails'])->name('task.pmts-images');
    Route::post('/update-status/{id}', [TaskController::class, 'updateStatus'])->name('task.update-status');

    Route::post('/complete/{id}', [TaskController::class, 'completeTask'])->name('task.complete');
    Route::post('/reopen/{id}', [TaskController::class, 'reopenTask'])->name('task.reopen');


    Route::post('/reopen-with-deadline', [TaskController::class, 'reopenWithDeadline'])->name('task.reopen-with-deadline');
});

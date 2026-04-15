<?php

use App\Http\Controllers\Dashboard\HR\Kanbanboard\SubtaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'subtask'], function () {
    Route::get('', [SubtaskController::class, 'subtasklist'])->name('subtasklist');
    Route::get('/create/{taskId?}', [SubtaskController::class, 'subtaskcreate'])->name('subtaskcreate');
    Route::post('/store', [SubtaskController::class, 'subtaskstore'])->name('subtaskstore');
    Route::get('/edit/{id}', [SubtaskController::class, 'subtaskedit'])->name('subtaskedit');
    Route::put('/update/{id}', [SubtaskController::class, 'subtaskupdate'])->name('subtaskupdate');
    Route::delete('/delete/{id}', [SubtaskController::class, 'subtaskdestroy'])->name('subtaskdestroy');


    Route::post('/update-status/{id}', [SubtaskController::class, 'updateStatus'])->name('subtask.update-status');
    Route::post('/pmts-images', [SubtaskController::class, 'getPmtsImageDetails'])->name('subtask.pmts-images');



    Route::post('/complete/{id}', [SubtaskController::class, 'completeSubtask'])->name('subtask.complete');
    Route::post('/reopen/{id}', [SubtaskController::class, 'reopenSubtask'])->name('subtask.reopen');
    Route::get('/details/{id}', [SubtaskController::class, 'getSubtaskDetails'])->name('subtask.details');
});

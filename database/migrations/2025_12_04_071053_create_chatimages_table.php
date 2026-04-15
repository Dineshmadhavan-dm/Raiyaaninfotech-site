<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chatimages')) {
            Schema::create('chatimages', function (Blueprint $table) {
                $table->id('chatimage_id');
                $table->unsignedBigInteger('task_id')->nullable();
                $table->unsignedBigInteger('subtask_id')->nullable();
                $table->enum('type', ['task', 'subtask'])->default('task');
                $table->string('file_name');
                $table->string('file_type');
                $table->integer('file_size');
                $table->boolean('delete_status')->default(1);
                $table->timestamps();

                $table->foreign('task_id')
                    ->references('task_id')
                    ->on('tasks')
                    ->onDelete('cascade');

                $table->foreign('subtask_id')
                    ->references('stask_id')
                    ->on('subtasks')
                    ->onDelete('cascade');

                $table->index(['type', 'delete_status']);
                $table->index('file_name');
                $table->index(['task_id', 'subtask_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('chatimages');
    }
};

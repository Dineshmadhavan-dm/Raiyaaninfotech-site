<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chattasks')) {
            Schema::create('chattasks', function (Blueprint $table) {
                $table->id('chat_id');
                $table->unsignedBigInteger('task_id')->nullable();
                $table->unsignedBigInteger('subtask_id')->nullable();
                $table->unsignedBigInteger('sender_id')->nullable();
                $table->unsignedBigInteger('receiver_id')->nullable();
                $table->longText('message')->nullable();
                $table->json('chatfile')->nullable();
                   $table->boolean('is_read')->default(0);
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

                $table->foreign('sender_id')
                    ->references('emp_id')
                    ->on('employees')
                    ->onDelete('cascade');

                $table->foreign('receiver_id')
                    ->references('emp_id')
                    ->on('employees')
                    ->onDelete('cascade');

                $table->index(['task_id', 'subtask_id']);
                $table->index('sender_id');
                $table->index('receiver_id');
                $table->index('delete_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('chattasks');
    }
};

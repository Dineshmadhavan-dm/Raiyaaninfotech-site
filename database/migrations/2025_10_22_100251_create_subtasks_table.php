<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id('stask_id');
            $table->foreignId('stask_task')->constrained('tasks', 'task_id');
            $table->string('stask_name');
            $table->longText('stask_desc')->nullable();
            $table->date('stask_deadline');
            $table->tinyInteger('stask_priority')->comment('1 => low , 2 => medium , 3 => high');
            $table->string('stask_avater')->nullable();
            $table->json('stask_attachment')->nullable();

            // CHANGED: stask_member to stask_assignedto for single person assignment
            $table->foreignId('stask_assignedto')->constrained('employees', 'emp_id');

            $table->integer('stask_overdue')->nullable();
            $table->date('stask_onprogress')->nullable();
            $table->date('stask_complete')->nullable();
            $table->tinyInteger('stask_status')->default(0)->comment('0 => created , 1 => onprogress , 2 => completed ');
            $table->boolean('stask_accessmod')->default(0)->comment('0 => public , 1 => private');

            $table->boolean('stask_emailvia')->default(0)->comment('0 => unsend , 1 => send');

            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};

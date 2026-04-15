<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->foreignId('task_modulo')->constrained('modulos', 'mod_id');
            $table->string('task_name');
            $table->longText('task_desc')->nullable();
            $table->date('task_deadline');
            $table->tinyInteger('task_priority')->comment('1 => low , 2 => medium , 3 => high');
            $table->string('task_avater')->nullable();
            $table->json('task_attachment')->nullable();
            // CHANGED: task_member to task_assignedto and removed json type
            $table->foreignId('task_assignedto')->constrained('employees', 'emp_id');
            $table->tinyInteger('task_status')->default(0)->comment('0 => created , 1 => onprogress , 2 => completed ');
            $table->integer('task_overdue')->nullable();
            $table->date('task_onprogress')->nullable();
            $table->date('task_complete')->nullable();
            $table->boolean('task_accessmod')->default(0)->comment('0 => public , 1 => private');

            $table->boolean('task_emailvia')->default(0)->comment('0 => unsend , 1 => send');

            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('probations', function (Blueprint $table) {
            $table->id('probation_id');

            // Employee details
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->string('employee_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->date('probation_from')->nullable();

            // Evaluation scores
            $table->integer('knowledge_score')->nullable();
            $table->integer('skills_score')->nullable();
            $table->integer('quality_score')->nullable();
            $table->integer('productivity_score')->nullable();
            $table->integer('teamwork_score')->nullable();
            $table->integer('punctuality_score')->nullable();
            $table->integer('dependability_score')->nullable();
            $table->integer('communication_score')->nullable();

            // Evaluation notes
            $table->text('knowledge_notes')->nullable();
            $table->text('skills_notes')->nullable();
            $table->text('quality_notes')->nullable();
            $table->text('productivity_notes')->nullable();
            $table->text('teamwork_notes')->nullable();
            $table->text('punctuality_notes')->nullable();
            $table->text('dependability_notes')->nullable();
            $table->text('communication_notes')->nullable();

            // Supporting documents
            $table->string('supporting_documents')->nullable();

            // Evaluation result
            $table->integer('overall_rating')->nullable(); // 1: Not Satisfied, 2: Somewhat Satisfied, 3: Satisfied
            $table->integer('appropriate_option')->nullable(); // 1: Confirmed, 2: Extended, 3: Terminated

            // Additional information
            $table->text('comments')->nullable();
            $table->date('date_of_joined')->nullable();
            $table->date('probation_to')->nullable();
            $table->string('hr_signature')->nullable();
            $table->date('date_of_evaluation');

            // Status
            $table->boolean('delete_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probations');
    }
};
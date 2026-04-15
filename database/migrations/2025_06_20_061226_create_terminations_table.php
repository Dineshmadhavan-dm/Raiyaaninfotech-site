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
        Schema::create('terminations', function (Blueprint $table) {

            $table->id('termination_id');

            // Employee details
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->string('employee_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();

            // Termination details
            $table->string('proposed_designation')->nullable();
            $table->boolean('termination_type')->nullable();
            $table->text('reason_for_termination')->nullable();
            $table->text('supporting_documents')->nullable();
            $table->text('employee_statement')->nullable();

            $table->boolean('can_be_rehired')->nullable();
            $table->boolean('confirm_termination')->nullable();



            $table->text('rehire_conditions')->nullable();


            // Dates and signatures
            $table->boolean('acknowledgement')->nullable();
            $table->string('full_name')->nullable();
            $table->date('date_of_hire')->nullable();
            $table->date('termination_date')->nullable();


            // Signatures
            $table->string('employee_signature')->nullable();
            $table->string('manager_signature')->nullable();

            // Status
            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminations');
    }
};
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
        Schema::create('resignations', function (Blueprint $table) {
            $table->id('resignation_id');

            // Employee details
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->string('employee_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();

            // Resignation details
            $table->boolean('is_voluntary')->default(true);
            $table->boolean('has_notice_period')->default(true);
            $table->date('notice_start_date')->nullable();
            $table->date('notice_end_date')->nullable();
            $table->date('last_working_day')->nullable();
            $table->string('notice_period')->nullable();

            // Resignation reasons
            $table->string('resignation_reason')->nullable();
            $table->text('reason_other_details')->nullable();



            $table->boolean('acknowledgement')->nullable();
            $table->string('full_name')->nullable();
            $table->date('date_of_hire')->nullable();
            // Additional information
            $table->text('reason_details')->nullable();
            $table->string('resignation_document')->nullable();
            $table->string('employee_signature')->nullable();
            $table->boolean('can_be_rehired')->default(true);
            $table->string('management_signature')->nullable();
            $table->text('rehire_conditions')->nullable();

            // Dates
            $table->date('date_of_resignation');

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
        Schema::dropIfExists('resignations');
    }
};
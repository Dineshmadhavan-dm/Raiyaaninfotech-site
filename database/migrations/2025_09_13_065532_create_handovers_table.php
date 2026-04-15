<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('handovers', function (Blueprint $table) {
            $table->id('handover_id');

            // Handing over employee details
            $table->unsignedBigInteger('handover_employee_id');
            $table->foreign('handover_employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');
            $table->string('handover_employee_email')->nullable();
            $table->string('handover_department')->nullable();
            $table->string('handover_designation')->nullable();

            // Taking over employee details
            $table->unsignedBigInteger('takeover_employee_id');
            $table->foreign('takeover_employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');
            $table->string('takeover_employee_email')->nullable();
            $table->string('takeover_department')->nullable();
            $table->string('takeover_designation')->nullable();

            // Handover details
            $table->tinyInteger('reason')->nullable();
            $table->string('reason_other')->nullable(); // NEW: For "Other" reason text
            $table->date('handover_date');

            // Task details (stored as JSON)
            $table->json('tasks')->nullable();

            // Documents
            $table->string('other_documents')->nullable();
            $table->string('resignation_documents')->nullable();

            // Signatures
            $table->string('handover_signature')->nullable();
            $table->string('takeover_signature')->nullable();

            // Status
            $table->boolean('delete_status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handovers');
    }
};

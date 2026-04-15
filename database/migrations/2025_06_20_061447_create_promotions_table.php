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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('promotion_id');

            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');





            $table->string('emp_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();


            $table->string('proposed_designation')->nullable();
            $table->decimal('percentage_increase', 5, 2)->nullable();
            $table->text('supporting_documents')->nullable();
            $table->text('additional_comments')->nullable();



            $table->string('manager_name')->nullable();
            $table->date('date_of_signature')->nullable();
            $table->string('full_name')->nullable();
            $table->date('date_of_hire')->nullable();
            $table->string('proposed_new_salary')->nullable();
            $table->text('reason_for_promotion')->nullable();
            $table->string('manager_signature')->nullable();
            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

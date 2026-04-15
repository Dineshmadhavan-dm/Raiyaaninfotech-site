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
        Schema::create('education', function (Blueprint $table) {
            $table->id('edu_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->string('qualification');
            $table->string('name_of_institution');
            $table->string('edu_location');
            $table->date('edu_from_date');
            $table->date('edu_to_date');
            $table->string('specialization');
            $table->decimal('percentage_grade', 5, 2);




            $table->boolean('delete_status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};

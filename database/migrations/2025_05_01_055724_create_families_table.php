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
        Schema::create('families', function (Blueprint $table) {
            $table->id('family_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->string('fa_name')->nullable();
            $table->string('fa_relation')->nullable();
            $table->string('fa_occupation')->nullable();

            $table->boolean('delete_status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};

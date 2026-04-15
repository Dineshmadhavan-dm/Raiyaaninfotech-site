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
        Schema::create('leavetypes', function (Blueprint $table) {
            $table->id('leavetype_id');

            $table->string('employee_name_id')->nullable();
            $table->string('department_name_id')->nullable();
            $table->tinyInteger('leavetype_name_id')->nullable();
            $table->date('leave_start_from')->nullable();
            $table->date('leave_end_to')->nullable();


            //dropdown leave type with days textbox
            $table->string('leavetype_name')->nullable();
            $table->integer('leave_days')->nullable();
            $table->json('column_preferences')->nullable();
            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leavetypes');
    }
};

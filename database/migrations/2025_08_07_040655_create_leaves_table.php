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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id('leave_id');

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');


            $table->unsignedBigInteger('attend_id')->nullable();
            $table->foreign('attend_id')
                ->references('attendance_id')
                ->on('attendances')
                ->onDelete('cascade');




            $table->string('member')->nullable();


            $table->string('leave_type_id')->nullable();

            $table->tinyInteger('select_duration')->nullable()->comment('1 => date , 2 => multiple , 3 => firsthalf , 4 => secondhalf ');

            $table->tinyInteger('leave_status')->nullable()->default(2)->comment('1 => approved , 2 => pending , 3 => reject');


            $table->longText('reason_forleave')->nullable();
            $table->longText('leave_file')->nullable();



            $table->date('leavedate_no')->nullable();
            $table->date('leavedaterange_from')->nullable();
            $table->date('leavedaterange_to')->nullable();

            $table->boolean('delete_status')->default(1);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};

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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');


            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');



            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('clock_in_ip')->nullable();
            $table->string('clock_out_ip')->nullable();


            $table->tinyInteger('attendance_workfrom')->nullable()->comment('0 => home , 1 => office');

            $table->tinyInteger('attendance_type')->nullable()->comment('0 => absent, 1 => present ,  2 => late , 3 => halfday');

            $table->tinyInteger('half_day_type')->nullable()->comment(' 0 => firsthalf , 1 => secondhalf');

            $table->tinyInteger('mark_attendance')->nullable()->default(0)->comment('1 => multiple , 2 => month');





            $table->string('attendance_loc')->nullable();
            $table->time('early_clock_in_time')->nullable();
            $table->time('early_clock_out_time')->nullable();

            $table->string('attendance_depname')->nullable();
            $table->string('attendance_depadmin')->nullable();
            $table->string('attendance_empname')->nullable();



            $table->date('attendancedate_no')->nullable();
            $table->date('attenddaterange_from')->nullable();
            $table->date('attenddaterange_to')->nullable();

            $table->string('month_year')->nullable();

            $table->boolean('delete_status')->default(1);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

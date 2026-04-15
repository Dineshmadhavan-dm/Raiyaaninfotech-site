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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('shift_id');



            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');




            $table->string('department_name')->nullable();
            $table->string('department_admin')->nullable();
            $table->string('employee_name')->nullable();


            $table->time('shift_from_time')->nullable();
            $table->time('shift_to_time')->nullable();

$table->tinyInteger('shift_type')->nullable()
    ->comment('0 => GS, 1 => MS, 2 => NS, 3 => Dayoff ,4 => Ho');

$table->string('holiday_type')->nullable();
$table->string('occasion')->nullable();
            $table->tinyInteger('assign_shift')->nullable()->comment('0 => Date, 1 => Multiple, 2 => Month');
            $table->date('date_no')->nullable();
            $table->date('date_range_from')->nullable();
            $table->date('date_range_to')->nullable();

            $table->string('month_year')->nullable();



            $table->string('notes')->nullable();

            $table->boolean('delete_status')->default(1);






            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};

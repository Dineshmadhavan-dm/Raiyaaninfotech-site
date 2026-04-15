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
        Schema::create('pastemployees', function (Blueprint $table) {
            $table->id('pastemp_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('cascade');

            $table->boolean('employed_as')->comment('0 = New, 1 = Experienced');
            $table->boolean('has_uan')->nullable()->comment('0 = No, 1 = Yes');



            $table->string('past_organisation_name')->nullable();
            $table->string('past_location')->nullable();
            $table->string('past_department')->nullable();
            $table->string('past_designation')->nullable();
            $table->string('past_role')->nullable();

            $table->string('past_annual_ctc')->nullable();

            $table->date('past_from_date')->nullable();
            $table->date('past_to_date')->nullable();
            $table->string('uan_number')->nullable();
            $table->boolean('delete_status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastemployees');
    }
};

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('emp_id');
            $table->string('employee_id')->unique()->nullable();


            $table->string('fullname');
            $table->string('fathername')->nullable();
            $table->string('image')->nullable();

            $table->text('address')->nullable();
            $table->text('flatno')->nullable();
            $table->text('street')->nullable();



            $table->string('personal_email');

            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            $table->string('personal_mobile');
            $table->string('bloodgroup')->nullable();

            $table->boolean('gender')->comment('0 = male, 1 = female');
            $table->boolean('marital_status')->nullable()->comment('0 = single, 1 = married');

            $table->date('dob')->nullable();
            $table->string('pancard_no')->nullable();
            $table->string('aadhaar_no')->nullable();
            $table->json('column_preferences')->nullable();
            $table->tinyInteger('status_for_stepform')->default(1)->comment('1=personal, 2=family, 3=education, 4=pastemp, 5=currentemp, 6=reference');


            $table->string('c_person_emergency')->nullable();
            $table->string('relationship')->nullable();
            $table->string('emergency_contact')->nullable();








            $table->string('cur_department')->nullable();
            $table->string('cur_designation')->nullable();
            $table->boolean('login_access')->nullable()->comment('0 = No, 1 = Yes');

            $table->string('cur_location')->nullable();

            $table->string('jobtype')->nullable();
            $table->string('email_company')->nullable();
            $table->string('password_company')->nullable();
            $table->string('cur_annual_ctc')->nullable();


            $table->string('inmonth')->nullable();


            $table->date('dojprovision_from_date')->nullable();
            $table->date('provision_to_date')->nullable();


            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');







            $table->boolean('delete_status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
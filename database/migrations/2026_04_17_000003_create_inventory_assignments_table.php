<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('inventory_assignments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('employee_id');
    $table->unsignedBigInteger('department_id');
    $table->date('assigned_date');
    $table->date('return_date')->nullable();

    $table->tinyInteger('status')
          ->default(0)
          ->comment('0=assigned,1=returned');



    $table->text('remarks')->nullable();
    $table->timestamps();

    $table->tinyInteger('condition_status')
          ->default(1)
          ->comment('0=scrap,1=active');
    $table->foreign('item_id')->references('id')->on('inventory_items')->cascadeOnDelete();
    $table->foreign('employee_id')->references('emp_id')->on('employees')->cascadeOnDelete();
    $table->foreign('department_id')->references('dep_id')->on('departments')->cascadeOnDelete();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_assignments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->unsignedBigInteger('employee_id')->nullable(); // ✅ ADD THIS
            $table->text('issue_description');
       $table->tinyInteger('maintenance_type')
      ->nullable()
      ->comment('0=scrap,1=service,2=upgrade');

$table->decimal('cost', 10, 2)->nullable();

$table->string('vendor_name')->nullable();

$table->date('start_date')->nullable();
$table->date('end_date')->nullable();

$table->tinyInteger('status')
      ->default(0)
      ->comment('0=pending,1=complete');
            $table->text('remarks')->nullable();
             $table->string('document')->nullable();
            $table->timestamps();

            // ✅ ADD FOREIGN KEY
            $table->foreign('employee_id')
                  ->references('emp_id')
                  ->on('employees')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenance');
    }
};

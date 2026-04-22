<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
           $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('action_type', ['created','assigned','returned','damaged','repaired']);

$table->tinyInteger('action_type')
      ->default(0)
      ->comment('0=assigned,1=returned,2=scrap,3=service,4=upgrade');


            $table->timestamp('action_date')->useCurrent();
            $table->foreign('employee_id')
      ->references('emp_id')
      ->on('employees')
      ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_history');
    }
};

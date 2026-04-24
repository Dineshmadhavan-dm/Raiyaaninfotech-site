<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->id();

            // 🔗 REFERENCES
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('maintenance_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            // 🧠 MODULE + ACTION
            $table->string('module');
            // item / assignment / maintenance / category

            $table->string('action');
            // created, updated, assigned, returned

            $table->string('sub_action')->nullable();
            // service, upgrade, scrap (for maintenance)

            // 📦 FULL SNAPSHOT DATA
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            // 📅 TIMESTAMP
            $table->timestamp('action_date')->useCurrent();

            $table->timestamps();

            // 🔗 FOREIGN KEYS (optional but recommended)
            $table->foreign('item_id')->references('id')->on('inventory_items')->nullOnDelete();
            $table->foreign('employee_id')->references('emp_id')->on('employees')->nullOnDelete();
            $table->foreign('assignment_id')->references('id')->on('inventory_assignments')->nullOnDelete();
            $table->foreign('maintenance_id')->references('id')->on('inventory_maintenance')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('inventory_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_history');
    }
};

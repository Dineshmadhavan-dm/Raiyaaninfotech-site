<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('item_code')->unique();
            $table->foreignId('category_id')->constrained('inventory_categories')->cascadeOnDelete();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('warranty_expiry')->nullable();

            $table->integer('quantity')->default(1);
       $table->tinyInteger('item_type') ->default(0) ->comment('0=new,1=refurbished');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->string('item_image')->nullable();
             $table->string('document_file')->nullable();
             $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

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
        Schema::create('pmts_images', function (Blueprint $table) {
            $table->id('pmts_id');
            $table->string('pmtsimage_name')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->enum('menu_type', ['project', 'modulo', 'task', 'subtask'])->nullable();
            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmts_images');
    }
};
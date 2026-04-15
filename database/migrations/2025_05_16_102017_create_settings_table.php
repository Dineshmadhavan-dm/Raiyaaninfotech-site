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
        Schema::create('settings', function (Blueprint $table) {
            $table->id('set_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('weblogo')->nullable();
            $table->string('webname')->nullable();
            $table->string('favlogo')->nullable();
            $table->string('p_color')->nullable()->default('#4477b6')->comment('primary color for throughout site');
            $table->string('h_color')->nullable()->default('#ffffff')->comment('header color for sidebar attached header');
            $table->string('nh_color')->nullable()->default('#ffffff')->comment('navigation header color');
            $table->string('s_color')->nullable()->default('#ffffff')->comment('sidebar color');




            $table->string('selected_palette')->nullable()->comment('selected color palette name');





            $table->boolean('enable_palettes')->default(false)->comment('whether palettes are enabled');
            $table->boolean('enable_custom_colors')->default(true)->comment('whether custom colors are enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

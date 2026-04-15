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
        Schema::create('projects', function (Blueprint $table) {
            $table->id('pro_id');
            $table->string('pro_name');
            $table->longText('pro_desc')->nullable();
            $table->string('pro_avater')->nullable();
            $table->date('pro_deadline');
            $table->string('pro_client');
            $table->string('pro_head');
            $table->string('pro_lead');
            $table->json('pro_member');
            $table->json('pro_attachment')->nullable();

            $table->tinyInteger('pro_status')->default(0)->comment('0 => created , 1 => onprogress , 2 => completed ');
            $table->integer('pro_overdue')->nullable();

            $table->date('pro_onprogress')->nullable();
            $table->date('pro_complete')->nullable();


            $table->boolean('pro_accessmod')->default(0)->comment('0 => public , 1 => private');

            $table->boolean('pro_emailvia')->default(0)->comment('0 => unsend , 1 => send');

            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

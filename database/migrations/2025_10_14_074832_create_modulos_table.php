<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id('mod_id');
            $table->foreignId('mod_project')->constrained('projects', 'pro_id');
            $table->string('mod_name');
            $table->longText('mod_desc')->nullable();
            $table->date('mod_deadline');

            // Add avatar field
            $table->string('mod_avater')->nullable();

            // Change attachment to JSON for storing PMTS IDs
            $table->json('mod_attachment')->nullable();

            $table->json('mod_member');

            $table->tinyInteger('mod_status')->default(0)->comment('0 => created , 1 => onprogress , 2 => completed ');
            $table->integer('mod_overdue')->nullable();

            $table->date('mod_onprogress')->nullable();
            $table->date('mod_complete')->nullable();

            $table->boolean('mod_accessmod')->default(0)->comment('0 => public , 1 => private');

            $table->boolean('mod_emailvia')->default(0)->comment('0 => unsend , 1 => send');

            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};

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
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('employeerole_id')
                ->nullable()
                ->after('id');

            // 2. Add foreign key constraint
            $table->foreign('employeerole_id')
                ->references('emp_id')
                ->on('employees')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Drop the foreign key first (to avoid errors)
            $table->dropForeign(['employeerole_id']);

            // 2. Drop the column
            $table->dropColumn('employeerole_id');
        });
    }
};

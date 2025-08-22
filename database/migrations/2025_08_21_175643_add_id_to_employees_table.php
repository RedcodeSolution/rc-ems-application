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
        // This migration is not needed because the 'id' primary key already exists.
        // Schema::table('employees', function (Blueprint $table) {
        //     $table->bigIncrements('id')->first();
        // });
    }

    public function down(): void
    {
        // Schema::table('employees', function (Blueprint $table) {
        //     $table->dropColumn('id');
        // });
    }

};

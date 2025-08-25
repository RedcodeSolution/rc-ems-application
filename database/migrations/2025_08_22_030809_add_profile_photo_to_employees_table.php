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
        // Column already exists, so do nothing or comment out the code below
        // Schema::table('employees', function (Blueprint $table) {
        //     $table->string('profile_photo')->nullable();
        // });
    }

    public function down(): void
    {
        // If you want to remove the column on rollback, only do so if it exists
        // if (Schema::hasColumn('employees', 'profile_photo')) {
        //     Schema::table('employees', function (Blueprint $table) {
        //         $table->dropColumn('profile_photo');
        //     });
        // }
    }
};

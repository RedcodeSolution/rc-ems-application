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
        // Only drop the column if it exists
        if (Schema::hasColumn('employees', 'date_of_birth')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('date_of_birth');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, add the column back if needed
        if (!Schema::hasColumn('employees', 'date_of_birth')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->date('date_of_birth')->nullable();
            });
        }
    }
};

<?php

// In a new migration file
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
        // Only add the column if it does not exist
        if (!Schema::hasColumn('employees', 'address')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('address')->nullable()->after('contact_no');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'address')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
    }
};

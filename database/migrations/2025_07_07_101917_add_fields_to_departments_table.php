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
        Schema::table('departments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('department_name');
            $table->string('department_head')->nullable()->after('description');
            $table->string('location')->nullable()->after('department_head');
            $table->string('phone')->nullable()->after('location');
            $table->string('email')->nullable()->after('phone');
            $table->decimal('budget', 15, 2)->nullable()->after('email');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'department_head',
                'location',
                'phone',
                'email',
                'budget',
                'status'
            ]);
        });
    }
};

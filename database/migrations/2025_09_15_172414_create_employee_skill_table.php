<?php

use App\Models\Employee;
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
        Schema::create('employee_skill', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            // Use foreignIdFor for cleaner syntax with foreign keys
            $table->foreignIdFor(Employee::class)->constrained('employee_id')->onDelete('cascade');
            $table->string('skill_name');
            $table->string('skill_level')->nullable(); // New field for proficiency
            $table->string('skill_category')->nullable(); // New field for category
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_skill');
    }
};

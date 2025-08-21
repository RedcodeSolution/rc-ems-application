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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_id')->primary();
            $table->string('employee_name');
            $table->string('employee_type');
            $table->string('employee_status');
            $table->string('contact_no');
            $table->string('department_id')->nullable();
            $table->string('admin_id')->nullable();
            $table->string('paid_status');
            $table->string('team_id')->nullable();
            $table->string('role');
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
            $table->foreign('admin_id')->references('admin_id')->on('admins')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_project', function (Blueprint $table) {
            $table->string('employee_id');
            $table->string('project_id');
            $table->timestamp('assigned_date')->nullable();
            $table->string('role_in_project', 100);
            $table->primary(['employee_id', 'project_id']);
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('cascade');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_project');
    }
};

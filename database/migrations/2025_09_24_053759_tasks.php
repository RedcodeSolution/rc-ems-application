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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->string('project_id');

            $table->enum('status', ['todo', 'in_progress', 'completed', 'overdue', 'on_hold'])->default('todo');

            $table->integer('progress')->default(0);
            $table->text('personal_notes')->nullable();
            $table->timestamps();

            $table->foreign('assigned_by')->references('employee_id')->on('employees')->onDelete('set null');
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

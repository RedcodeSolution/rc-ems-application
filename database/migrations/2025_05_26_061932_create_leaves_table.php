<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->string('leave_id')->primary();
            $table->unsignedBigInteger('employee_id');
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration')->nullable();
            $table->text('reason');
            $table->string('contact_number')->nullable();
            $table->string('supporting_doc')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('applied_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('admin_id')->on('admins')->onDelete('set null');
            $table->foreign('rejected_by')->references('admin_id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};

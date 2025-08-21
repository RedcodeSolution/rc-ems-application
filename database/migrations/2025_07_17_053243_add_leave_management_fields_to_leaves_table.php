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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('leave_type')->default('sick_leave'); // sick_leave, annual_leave, casual_leave, emergency_leave
            $table->integer('duration')->default(1); // Duration in days
            $table->string('contact_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('applied_date')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->string('rejected_by')->nullable();
            $table->timestamp('rejected_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('comments')->nullable();
            
            $table->foreign('approved_by')->references('admin_id')->on('admins')->onDelete('set null');
            $table->foreign('rejected_by')->references('admin_id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'leave_type',
                'duration',
                'contact_number',
                'status',
                'applied_date',
                'approved_by',
                'approved_date',
                'rejected_by',
                'rejected_date',
                'rejection_reason',
                'comments'
            ]);
        });
    }
};

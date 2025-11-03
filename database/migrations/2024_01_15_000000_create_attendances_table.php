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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'half_day'])->default('present');
            $table->decimal('hours_worked', 8, 2)->nullable();
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->text('clock_in_note')->nullable();
            $table->text('clock_out_note')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('date');
            $table->index('status');
            $table->unique(['user_id', 'date']);

            // Break tracking
            $table->timestamp('break_start_time')->nullable();
            $table->timestamp('break_end_time')->nullable();
            $table->decimal('break_duration', 8, 2)->default(0);
            $table->boolean('is_on_break')->default(false);

            // Emergency tracking
            $table->boolean('is_on_emergency')->default(false);
            $table->string('emergency_type')->nullable();
            $table->text('emergency_description')->nullable();
            $table->timestamp('emergency_start_time')->nullable();
            $table->timestamp('emergency_end_time')->nullable();
            $table->decimal('emergency_duration', 8, 2)->default(0);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

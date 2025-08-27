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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_link');
            $table->date('meeting_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->enum('type', ['daily_standup', 'weekly', 'monthly', 'special'])->default('daily_standup');
            $table->boolean('is_recurring')->default(true);
            $table->json('recurring_days')->nullable(); // For storing which days of week
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

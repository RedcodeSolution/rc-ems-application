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
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('team_id');
            $table->string('team_name');
            $table->integer('max_team_size')->default(0);
            $table->decimal('monthly_budget', 12, 2)->nullable();
            $table->enum('team_status', ['Active', 'Inactive'])->default('Active');
            $table->enum('team_priority', ['Low', 'Normal', 'High'])->default('Normal');
            $table->string('team_location')->nullable();
            $table->enum('work_mode', ['On-site', 'Remote', 'Hybrid'])->default('On-site');
            $table->text('team_description')->nullable();
            $table->text('team_goals')->nullable();
            $table->text('skills_required')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

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
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('team_lead')->nullable();
            $table->integer('max_team_size')->default(0);
            $table->decimal('monthly_budget', 12, 2)->nullable();
            $table->enum('team_status', ['Active', 'Inactive', 'On Hold', 'Disbanded'])->default('Active');
            $table->enum('team_priority', ['Low', 'Normal', 'High', 'Critical'])->default('Normal');
            $table->enum('work_mode', ['On-site', 'Remote', 'Hybrid', 'Flexible'])->default('On-site');
            $table->text('team_description')->nullable();
            $table->text('team_goals')->nullable();
            $table->text('skills_required')->nullable();
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
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

<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {


        Schema::create('projects', function (Blueprint $table) {
            $table->string('project_id')->primary();
            $table->string('project_name');
            $table->text('description')->nullable();
            $table->string('client')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->enum('status', ['Planning', 'In Progress', 'On Hold', 'Testing', 'Completed', 'Cancelled']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('milestone_info')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('set null');
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('projects');
    }
};

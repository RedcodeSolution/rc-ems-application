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
        Schema::create('reports', function (Blueprint $table) {
            $table->string('report_id')->primary();
            $table->string('report_name');
            $table->string('report_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('report_format')->default('pdf');
            $table->string('priority')->default('normal');
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');


    }


};

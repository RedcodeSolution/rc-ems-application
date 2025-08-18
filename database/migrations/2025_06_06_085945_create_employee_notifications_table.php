<?php

// database/migrations/xxxx_xx_xx_create_employee_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notifi_id');
            $table->string('employee_id');
            $table->timestamps();

            $table->foreign('notifi_id')->references('notifi_id')->on('notifications')->onDelete('cascade');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_notifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admins', function (Blueprint $table) {
            $table->string('super_admin_id')->primary();
            $table->string('super_admin_name');
            $table->string('super_admin_email')->unique();
            $table->string('password');
            $table->text('permissions')->nullable();
            $table->string('status')->default('active');
            $table->string('role')->default('super_admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admins');
    }
};

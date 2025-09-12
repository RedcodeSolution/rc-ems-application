<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admin_activities', function (Blueprint $table) {
            $table->id();
            $table->string('super_admin_id');
            $table->string('type');
            $table->string('icon');
            $table->string('action');
            $table->text('details');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admin_activities');
    }
};


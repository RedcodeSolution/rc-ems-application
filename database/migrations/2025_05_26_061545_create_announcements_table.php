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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('announcement_id');
            $table->string('title'); // from form
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('category'); // hr, policy, events etc.
            $table->text('content');
            $table->dateTime('expires_at')->nullable();
            $table->json('target_audience')->nullable(); // stores ["all","managers"]
            $table->enum('status', ['scheduled', 'published'])->default('published');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();

            $table->foreign('department_id')
                ->references('department_id')
                ->on('departments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

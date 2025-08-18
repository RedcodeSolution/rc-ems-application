<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_announce_details', function (Blueprint $table) {
            $table->id();
            $table->string('announcement_id');
            $table->string('team_id');
            $table->timestamps();

            $table->foreign('announcement_id')->references('announcement_id')->on('announcements')->onDelete('cascade');
            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_announce_details');
    }
};

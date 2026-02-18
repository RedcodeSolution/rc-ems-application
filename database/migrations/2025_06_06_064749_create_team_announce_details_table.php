<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_announce_details', function (Blueprint $table) {
            $table->id('team_announce_detail_id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('announcement_id');
            $table->timestamps();

            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
            $table->foreign('announcement_id')->references('announcement_id')->on('announcements')->onDelete('cascade');
        });
    } // <-- Make sure this closing brace is present

    public function down(): void
    {
        Schema::dropIfExists('team_announce_details');
    }
};

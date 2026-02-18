<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // In database/migrations/xxxx_xx_xx_create_dep_announce_details_table.php

    public function up(): void
    {
        Schema::create('dep_announce_details', function (Blueprint $table) {
            $table->id('dep_announce_detail_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('announcement_id'); // <-- Make sure this matches announcements table
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('announcement_id')->references('announcement_id')->on('announcements')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dep_announce_details');
    }
};

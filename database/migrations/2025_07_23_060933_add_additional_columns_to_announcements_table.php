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
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('announcement_title')->nullable()->after('announcement_id');
            $table->text('announcement_text')->nullable()->after('content');
            $table->enum('status', ['draft', 'pending', 'active', 'archived'])->default('active')->after('date');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['announcement_title', 'announcement_text', 'status', 'priority']);
        });
    }
};

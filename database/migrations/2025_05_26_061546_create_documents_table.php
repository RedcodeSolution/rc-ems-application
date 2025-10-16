<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->string('document_id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('access_level', ['public', 'department', 'admin', 'restricted']);
            $table->string('tags')->nullable();
            $table->string('file_path');
            $table->boolean('notify_users')->default(false);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('downloads');
            $table->dropColumn('employee_id');
        });
    }
};

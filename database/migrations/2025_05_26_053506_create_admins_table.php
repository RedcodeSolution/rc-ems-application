<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id('admin_id'); // Primary Key
                $table->string('admin_name');
                $table->string('role')->nullable();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('email')->unique();
                $table->string('contact_no');
                $table->enum('status', ['Active', 'Inactive', 'On Leave', 'Terminated'])->default('Active');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};


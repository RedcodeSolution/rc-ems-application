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
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'employee_id')) {
                    $table->unsignedBigInteger('employee_id')->nullable()->after('id');
                    // Add foreign key if employees table exists
                    if (Schema::hasTable('employees')) {
                        $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');
                    }
                }
            });
        } else {
            // If attendances table doesn't exist, create a minimal attendances table with employee_id
            Schema::create('attendances', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->dateTime('check_in')->nullable();
                $table->dateTime('check_out')->nullable();
                $table->string('status')->nullable();
                $table->integer('total_hours')->nullable();
                $table->date('date')->nullable();
                $table->timestamps();

                if (Schema::hasTable('employees')) {
                    $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasColumn('attendances', 'employee_id')) {
                    // drop foreign key if exists
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $doctrineTable = $sm->listTableDetails('attendances');
                    if ($doctrineTable->hasForeignKey('attendances_employee_id_foreign')) {
                        $table->dropForeign('attendances_employee_id_foreign');
                    }
                    $table->dropColumn('employee_id');
                }
            });
        }
    }
};


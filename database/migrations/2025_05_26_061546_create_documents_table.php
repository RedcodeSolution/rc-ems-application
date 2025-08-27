<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->bigIncrements('document_id');
                $table->unsignedBigInteger('employee_id');
                $table->string('file_path');
                $table->string('document_type');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

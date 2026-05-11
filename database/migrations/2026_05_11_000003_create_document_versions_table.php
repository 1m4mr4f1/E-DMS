<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->integer('version');
            $table->text('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type', 127);
            $table->string('checksum', 64);
            $table->foreignId('uploaded_by')->constrained('users');
            $table->text('change_note')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('title');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('division_id');
            $table->enum('visibility', ['division_only', 'company_wide'])->default('division_only');
            $table->date('expires_at')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->foreign('folder_id')->references('id')->on('folders')->nullOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('division_id')->references('id')->on('divisions')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

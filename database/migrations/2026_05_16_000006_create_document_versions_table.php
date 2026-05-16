<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('file_path');
            $table->string('file_original_name');
            $table->bigInteger('file_size_bytes');
            $table->string('file_mime_type');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('label_snapshot');
            $table->string('visibility_snapshot');
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->unique(['document_id', 'version_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_versions');
    }
}

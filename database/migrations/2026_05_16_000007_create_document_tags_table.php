<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTagsTable extends Migration
{
    public function up()
    {
        Schema::create('document_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('tag');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['document_id', 'tag']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_tags');
    }
}

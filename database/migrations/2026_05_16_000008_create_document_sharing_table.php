<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSharingTable extends Migration
{
    public function up()
    {
        Schema::create('document_sharing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('shared_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shared_to_division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('shared_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('permission', ['view', 'download']);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_sharing');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentActivityLogTable extends Migration
{
    public function up()
    {
        Schema::create('document_activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('document_version_id')->nullable()->constrained('document_versions')->nullOnDelete();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->string('action_type');
            $table->jsonb('old_snapshot')->nullable();
            $table->jsonb('new_snapshot')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // immutable: no updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_activity_log');
    }
}

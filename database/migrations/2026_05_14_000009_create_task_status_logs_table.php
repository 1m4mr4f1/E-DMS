<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_assignment_id');
            $table->unsignedBigInteger('changed_by');
            $table->string('old_status');
            $table->string('new_status');
            $table->text('note')->nullable();
            $table->foreign('task_assignment_id')->references('id')->on('task_assignments')->cascadeOnDelete();
            $table->foreign('changed_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_status_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('folders')
                ->nullOnDelete();
            $table->string('name', 255);
            $table->enum('division', ['HR', 'LEGAL', 'FINANCE', 'QA', 'ISO', 'GENERAL']);
            $table->text('path_cache')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};

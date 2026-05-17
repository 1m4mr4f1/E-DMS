<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_activity_log', function (Blueprint $table) {
            // Menambahkan kolom deskripsi untuk mencatat detail spesifik aksi
            $table->string('description')->nullable()->after('action_type');
        });
    }

    public function down(): void
    {
        Schema::table('document_activity_log', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
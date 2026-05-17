<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE document_sharing DROP CONSTRAINT IF EXISTS document_sharing_permission_check');
        } catch (\Exception $e) {
        }

        DB::statement("ALTER TABLE document_sharing ADD CONSTRAINT document_sharing_permission_check CHECK (permission IN ('view', 'edit'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke aturan lama jika migrasi di-rollback
        DB::statement('ALTER TABLE document_sharing DROP CONSTRAINT IF EXISTS document_sharing_permission_check');
        DB::statement("ALTER TABLE document_sharing ADD CONSTRAINT document_sharing_permission_check CHECK (permission IN ('view', 'download'))");
    }
};
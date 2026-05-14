<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE employees ALTER COLUMN division_id DROP NOT NULL');
        DB::statement('ALTER TABLE documents ALTER COLUMN division_id DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE documents ALTER COLUMN division_id SET NOT NULL');
        DB::statement('ALTER TABLE employees ALTER COLUMN division_id SET NOT NULL');
    }
};

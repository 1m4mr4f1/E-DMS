<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['name' => 'HR', 'code' => 'HR'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'IT', 'code' => 'IT'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Management', 'code' => 'MGT'],
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->updateOrInsert(
                ['code' => $division['code']],
                ['name' => $division['name'], 'code' => $division['code']]
            );
        }
    }
}

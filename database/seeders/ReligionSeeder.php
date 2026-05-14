<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        foreach ($religions as $name) {
            DB::table('religions')->updateOrInsert(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }
}

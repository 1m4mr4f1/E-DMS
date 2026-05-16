<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $categories = ['SOP', 'Laporan', 'Kebijakan', 'Kontrak', 'Keuangan'];

        $creatorId = DB::table('users')->value('id');

        foreach ($categories as $c) {
            DB::table('categories')->insert([
                'name' => $c,
                'color' => null,
                'created_by' => $creatorId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

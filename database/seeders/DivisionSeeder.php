<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $hrId = DB::table('divisions')->insertGetId(['code' => 'HR', 'name' => 'Human Resources', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        $itId = DB::table('divisions')->insertGetId(['code' => 'IT', 'name' => 'Information Technology', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        $finId = DB::table('divisions')->insertGetId(['code' => 'FIN', 'name' => 'Finance', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);

        DB::table('divisions')->insert([
            'code' => 'HR-REC',
            'name' => 'HR - Recruitment',
            'parent_division_id' => $hrId,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}


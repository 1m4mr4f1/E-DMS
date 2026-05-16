<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $roles = [
            ['code' => 'admin', 'name' => 'Administrator', 'hierarchy_level' => 100],
            ['code' => 'manager', 'name' => 'Manager', 'hierarchy_level' => 80],
            ['code' => 'staff', 'name' => 'Staff', 'hierarchy_level' => 50],
            ['code' => 'viewer', 'name' => 'Viewer', 'hierarchy_level' => 20],
            ['code' => 'auditor', 'name' => 'Auditor', 'hierarchy_level' => 10],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->insert(array_merge($r, ['created_at' => $now, 'updated_at' => $now]));
        }
    }
}


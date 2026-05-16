<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $adminRole = DB::table('roles')->where('code', 'admin')->first();
        $itDivision = DB::table('divisions')->where('code', 'IT')->first();

        DB::table('users')->insert([
            'employee_id' => 'EMP-0001',
            'full_name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'division_id' => $itDivision->id,
            'role_id' => $adminRole->id,
            'avatar_url' => null,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $managerRole = DB::table('roles')->where('code', 'manager')->first();
        $hrDivision = DB::table('divisions')->where('code', 'HR')->first();

        DB::table('users')->insert([
            [
                'employee_id' => 'EMP-0002',
                'full_name' => 'Alice Manager',
                'email' => 'alice.manager@example.com',
                'password' => Hash::make('password'),
                'division_id' => $hrDivision->id,
                'role_id' => $managerRole->id,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'EMP-0003',
                'full_name' => 'Bob Staff',
                'email' => 'bob.staff@example.com',
                'password' => Hash::make('password'),
                'division_id' => $itDivision->id,
                'role_id' => DB::table('roles')->where('code', 'staff')->first()->id,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


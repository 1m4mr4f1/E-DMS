<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employees = DB::table('employees')
            ->select('id', 'email')
            ->orderBy('id')
            ->get();

        $roleOverrides = [
            'super.admin@example.com' => 'super_admin',
            'admin@example.com' => 'admin',
            'siti.aisyah@example.com' => 'manager',
        ];

        foreach ($employees as $employee) {
            $role = $roleOverrides[$employee->email] ?? 'employee';

            $user = User::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'password' => Hash::make('password123'),
                    'role' => $role,
                    'is_active' => true,
                    'last_login' => null,
                    'remember_token' => Str::random(10),
                ]
            );

            $user->assignRole($role);
        }
    }
}

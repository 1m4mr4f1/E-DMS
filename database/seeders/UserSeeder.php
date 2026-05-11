<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $roles = ['Admin', 'Manager', 'Editor', 'Viewer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Data Pengguna untuk Test Login
        $users = [
            [
                'name'     => 'Rafi Admin',
                'email'    => 'admin_rafi@mail.com',
                'role'     => 'Admin',
            ],
            [
                'name'     => 'Dhion Admin',
                'email'    => 'admin_dhion@mail.com',
                'role'     => 'Admin',
            ],
            [
                'name'     => 'Finance Manager',
                'email'    => 'manager_finance@mail.com',
                'role'     => 'Manager',
            ],
            [
                'name'     => 'Legal Editor',
                'email'    => 'editor_legal@mail.com',
                'role'     => 'Editor',
            ],
            [
                'name'     => 'HR Viewer',
                'email'    => 'viewer_hr@mail.com',
                'role'     => 'Viewer',
            ],
            [
                'name'     => 'QA Editor',
                'email'    => 'editor_qa@mail.com',
                'role'     => 'Editor',
            ],
        ];

        // 3. Masukkan ke Database
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']], 
                [
                    'name'     => $userData['name'],
                    'password' => Hash::make('password123'), 
                ]
            );

            // Assign Role menggunakan Spatie [cite: 163, 167]
            $user->assignRole($userData['role']);
        }
    }
}
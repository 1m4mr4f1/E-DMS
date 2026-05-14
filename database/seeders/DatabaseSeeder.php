<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReligionSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}

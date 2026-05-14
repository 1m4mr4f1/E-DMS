<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'HR' => ['Staff HR', 'Manager HR'],
            'FIN' => ['Staff Finance', 'Manager Finance'],
            'IT' => ['Developer', 'IT Manager'],
            'OPS' => ['Staff Operasional', 'Manager Operasional'],
            'MGT' => ['Direktur', 'General Manager'],
        ];

        foreach ($positions as $divisionCode => $names) {
            $division = DB::table('divisions')->where('code', $divisionCode)->first();
            if (! $division) {
                continue;
            }

            foreach ($names as $name) {
                DB::table('positions')->updateOrInsert(
                    ['name' => $name, 'division_id' => $division->id],
                    ['name' => $name, 'division_id' => $division->id]
                );
            }
        }
    }
}

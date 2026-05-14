<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $religions = DB::table('religions')->pluck('id', 'name')->toArray();
        $divisions = DB::table('divisions')->pluck('id', 'code')->toArray();
        $positions = DB::table('positions')->get();

        $positionMap = [];
        foreach ($positions as $position) {
            $positionMap[$position->name] = $position->id;
        }

        $items = [
            [
                'nip' => 'EMP-2024-000',
                'name' => 'Super Admin',
                'email' => 'super.admin@example.com',
                'phone' => '081200000000',
                'religion' => 'Islam',
                'division' => null,
                'position' => 'Direktur',
                'status' => 'active',
                'joined_at' => '2022-01-01',
            ],
            [
                'nip' => 'EMP-2024-0001',
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '081211111111',
                'religion' => 'Islam',
                'division' => null,
                'position' => 'General Manager',
                'status' => 'active',
                'joined_at' => '2022-02-01',
            ],
            [
                'nip' => 'EMP-2024-001',
                'name' => 'Ahmad Nur',
                'email' => 'ahmad.nur@example.com',
                'phone' => '081234567890',
                'religion' => 'Islam',
                'division' => 'HR',
                'position' => 'Staff HR',
                'status' => 'active',
                'joined_at' => '2024-01-15',
            ],
            [
                'nip' => 'EMP-2024-002',
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@example.com',
                'phone' => '081298765432',
                'religion' => 'Islam',
                'division' => 'HR',
                'position' => 'Manager HR',
                'status' => 'active',
                'joined_at' => '2023-11-01',
            ],
            [
                'nip' => 'EMP-2024-003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '082112345678',
                'religion' => 'Kristen',
                'division' => 'FIN',
                'position' => 'Staff Finance',
                'status' => 'active',
                'joined_at' => '2024-02-10',
            ],
            [
                'nip' => 'EMP-2024-004',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'phone' => '082299887766',
                'religion' => 'Katolik',
                'division' => 'FIN',
                'position' => 'Manager Finance',
                'status' => 'active',
                'joined_at' => '2023-12-05',
            ],
            [
                'nip' => 'EMP-2024-005',
                'name' => 'Rian Pratama',
                'email' => 'rian.pratama@example.com',
                'phone' => '081334455667',
                'religion' => 'Islam',
                'division' => 'IT',
                'position' => 'Developer',
                'status' => 'active',
                'joined_at' => '2024-03-20',
            ],
            [
                'nip' => 'EMP-2024-006',
                'name' => 'Maya Sari',
                'email' => 'maya.sari@example.com',
                'phone' => '081377788899',
                'religion' => 'Hindu',
                'division' => 'IT',
                'position' => 'IT Manager',
                'status' => 'active',
                'joined_at' => '2023-10-12',
            ],
            [
                'nip' => 'EMP-2024-007',
                'name' => 'Fajar Hidayat',
                'email' => 'fajar.hidayat@example.com',
                'phone' => '081455566778',
                'religion' => 'Buddha',
                'division' => 'OPS',
                'position' => 'Staff Operasional',
                'status' => 'active',
                'joined_at' => '2024-01-30',
            ],
            [
                'nip' => 'EMP-2024-008',
                'name' => 'Nina Wirawan',
                'email' => 'nina.wirawan@example.com',
                'phone' => '081466677788',
                'religion' => 'Konghucu',
                'division' => 'OPS',
                'position' => 'Manager Operasional',
                'status' => 'active',
                'joined_at' => '2023-11-20',
            ],
            [
                'nip' => 'EMP-2024-009',
                'name' => 'Hendra Putra',
                'email' => 'hendra.putra@example.com',
                'phone' => '081477788899',
                'religion' => 'Islam',
                'division' => 'MGT',
                'position' => 'Direktur',
                'status' => 'active',
                'joined_at' => '2022-09-01',
            ],
            [
                'nip' => 'EMP-2024-010',
                'name' => 'Dina Rahma',
                'email' => 'dina.rahma@example.com',
                'phone' => '081488899900',
                'religion' => 'Kristen',
                'division' => 'MGT',
                'position' => 'General Manager',
                'status' => 'active',
                'joined_at' => '2023-08-15',
            ],
        ];

        foreach ($items as $item) {
            $religionId = $religions[$item['religion']] ?? null;
            $divisionId = $item['division'] ? ($divisions[$item['division']] ?? null) : null;
            $positionId = $positionMap[$item['position']] ?? null;

            if (! $religionId || ! $positionId) {
                continue;
            }

            DB::table('employees')->updateOrInsert(
                ['nip' => $item['nip']],
                [
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'phone' => $item['phone'],
                    'religion_id' => $religionId,
                    'division_id' => $divisionId,
                    'position_id' => $positionId,
                    'status' => $item['status'],
                    'joined_at' => $item['joined_at'],
                ]
            );
        }
    }
}

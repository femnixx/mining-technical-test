<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operator;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $operators = [
            ['name' => 'Budi Santoso', 'license_number' => 'OP-2024-001', 'status' => 'On Shift'],
            ['name' => 'Ahmad Hidayat', 'license_number' => 'OP-2024-002', 'status' => 'On Shift'],
            ['name' => 'Rizky Pratama', 'license_number' => 'OP-2024-003', 'status' => 'Off Duty'],
            ['name' => 'Dedi Kurniawan', 'license_number' => 'OP-2024-004', 'status' => 'On Break'],
            ['name' => 'Fajar Nugroho', 'license_number' => 'OP-2024-005', 'status' => 'Off Duty'],
        ];

        foreach ($operators as $operator) {
            Operator::create($operator);
        }
    }
}

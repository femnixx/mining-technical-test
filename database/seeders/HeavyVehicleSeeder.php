<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeavyVehicle;

class HeavyVehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['vehicle_code' => 'HT-001', 'type' => 'Haul Truck', 'model' => 'CAT 793D', 'status' => 'Active', 'fuel_capacity_l' => 3500],
            ['vehicle_code' => 'HT-002', 'type' => 'Haul Truck', 'model' => 'Komatsu 930E', 'status' => 'Active', 'fuel_capacity_l' => 3200],
            ['vehicle_code' => 'HT-003', 'type' => 'Haul Truck', 'model' => 'CAT 775G', 'status' => 'Idle', 'fuel_capacity_l' => 2500],
            ['vehicle_code' => 'HT-004', 'type' => 'Haul Truck', 'model' => 'Liebherr T 282C', 'status' => 'Maintenance', 'fuel_capacity_l' => 3000],
            ['vehicle_code' => 'HT-005', 'type' => 'Haul Truck', 'model' => 'CAT 793F', 'status' => 'Active', 'fuel_capacity_l' => 3500],
            ['vehicle_code' => 'EX-001', 'type' => 'Excavator', 'model' => 'CAT 6090', 'status' => 'Active', 'fuel_capacity_l' => 1500],
            ['vehicle_code' => 'EX-002', 'type' => 'Excavator', 'model' => 'Komatsu PC8000', 'status' => 'Active', 'fuel_capacity_l' => 1400],
            ['vehicle_code' => 'EX-003', 'type' => 'Excavator', 'model' => 'Hitachi EX5600', 'status' => 'Idle', 'fuel_capacity_l' => 1300],
            ['vehicle_code' => 'DZ-001', 'type' => 'Dozer', 'model' => 'CAT D11T', 'status' => 'Active', 'fuel_capacity_l' => 800],
            ['vehicle_code' => 'DZ-002', 'type' => 'Dozer', 'model' => 'Komatsu D475A', 'status' => 'Active', 'fuel_capacity_l' => 750],
            ['vehicle_code' => 'DZ-003', 'type' => 'Dozer', 'model' => 'CAT D10T', 'status' => 'Maintenance', 'fuel_capacity_l' => 700],
            ['vehicle_code' => 'HT-006', 'type' => 'Haul Truck', 'model' => 'BelAZ 75710', 'status' => 'Active', 'fuel_capacity_l' => 5600],
        ];

        foreach ($vehicles as $vehicle) {
            HeavyVehicle::create($vehicle);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['model_name' => 'Toyota Hilux 4x4',
            'plate_number' => 'B 1234 MIN',
            'type' => 'passenger',
            'ownership' => 'owned',
            'location_id' => 3, // Site Borneo
            'fuel_consumption' => 12.5,
            'distance_km' => 0,
        ],
        [
            'model_name' => 'Mitsubishi Triton',
            'plate_number' => 'L 9988 REN',
            'type' => 'passenger',
            'ownership' => 'rented',
            'location_id' => 4, // Site Sumatra
            'fuel_consumption' => 11.2,
            'distance_km' => 0,
        ],
        // Cargo/Heavy Vehicles (Angkutan Barang)
        [
            'model_name' => 'Hino Ranger Dump Truck',
            'plate_number' => 'D 5566 TMB',
            'type' => 'cargo',
            'ownership' => 'owned',
            'location_id' => 5, // Site Sulawesi
            'fuel_consumption' => 45.0,
            'distance_km' => 0,
        ],
        [
            'model_name' => 'Scania P360 Mining Tipper',
            'plate_number' => 'B 7788 TMB',
            'type' => 'cargo',
            'ownership' => 'owned',
            'location_id' => 1, // Head Office (for display/standby)
            'fuel_consumption' => 50.5,
            'distance_km' => 0,
        ]
    ];

    foreach ($vehicles as $vehicle) { 
        \App\Models\Vehicle::create($vehicle);
    }
    }
}

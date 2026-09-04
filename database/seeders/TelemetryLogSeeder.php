<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeavyVehicle;
use App\Models\TelemetryLog;
use Carbon\Carbon;

class TelemetryLogSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = HeavyVehicle::all();

        foreach ($vehicles as $vehicle) {
            $startTime = Carbon::now()->subDay()->startOfHour();

            for ($i = 0; $i < 96; $i++) {
                $timestamp = $startTime->copy()->addMinutes($i * 15);

                if ($vehicle->status === 'Maintenance') {
                    $fuel = fake()->numberBetween(10, 40);
                    $speed = 0;
                    $temp = fake()->numberBetween(60, 90);
                    $tonnage = 0;
                } else {
                    $fuel = fake()->numberBetween(15, 95);
                    $speed = fake()->numberBetween(0, 45);
                    $temp = fake()->numberBetween(85, 115);
                    $tonnage = fake()->numberBetween(0, 250);
                }

                TelemetryLog::create([
                    'vehicle_id' => $vehicle->id,
                    'timestamp' => $timestamp,
                    'latitude' => fake()->randomFloat(6, -8, -6),
                    'longitude' => fake()->randomFloat(6, 140, 145),
                    'speed_kmh' => $speed,
                    'fuel_level_pct' => $fuel,
                    'engine_temp_c' => $temp,
                    'load_tonnage' => $tonnage,
                ]);
            }
        }
    }
}

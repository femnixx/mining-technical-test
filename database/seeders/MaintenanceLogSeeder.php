<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeavyVehicle;
use App\Models\MaintenanceLog;
use Carbon\Carbon;

class MaintenanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = HeavyVehicle::where('status', 'Maintenance')->get();

        foreach ($vehicles as $vehicle) {
            MaintenanceLog::create([
                'vehicle_id' => $vehicle->id,
                'reported_at' => Carbon::now()->subDays(3),
                'issue_description' => 'Hydraulic system pressure drop detected during routine inspection.',
                'priority' => 'Critical',
                'status' => 'In Progress',
            ]);
        }
    }
}

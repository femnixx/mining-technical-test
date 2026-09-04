<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTelemetryRequest;
use App\Models\HeavyVehicle;
use App\Models\TelemetryLog;
use App\Models\MaintenanceLog;
use Illuminate\Http\JsonResponse;

class TelemetryController extends Controller
{
    public function __invoke(StoreTelemetryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $vehicle = HeavyVehicle::where('vehicle_code', $validated['vehicle_code'])->firstOrFail();

        $log = TelemetryLog::create([
            'vehicle_id' => $vehicle->id,
            'timestamp' => now(),
            'latitude' => $validated['lat'],
            'longitude' => $validated['long'],
            'speed_kmh' => $validated['speed'] ?? null,
            'fuel_level_pct' => $validated['fuel'] ?? null,
            'engine_temp_c' => $validated['temp'] ?? null,
            'load_tonnage' => $validated['tonnage'] ?? null,
        ]);

        $alerts = [];

        if ($log->engine_temp_c !== null && $log->engine_temp_c > 105) {
            MaintenanceLog::create([
                'vehicle_id' => $vehicle->id,
                'reported_at' => now(),
                'issue_description' => 'High engine temperature detected: ' . $log->engine_temp_c . '°C',
                'priority' => 'Critical',
                'status' => 'Open',
            ]);
            $alerts[] = 'High engine temperature: ' . $log->engine_temp_c . '°C exceeds threshold of 105°C';
        }

        if ($log->fuel_level_pct !== null && $log->fuel_level_pct < 15) {
            $alerts[] = 'Low fuel level: ' . $log->fuel_level_pct . '% falls below 15% threshold';
        }

        if ($log->fuel_level_pct !== null) {
            $vehicle->update(['status' => $log->fuel_level_pct < 15 ? 'Maintenance' : 'Active']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'telemetry_id' => $log->id,
                'vehicle_code' => $vehicle->vehicle_code,
                'timestamp' => $log->timestamp,
            ],
            'alerts' => $alerts,
        ], 201);
    }
}

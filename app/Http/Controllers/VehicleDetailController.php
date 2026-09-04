<?php

namespace App\Http\Controllers;

use App\Models\HeavyVehicle;
use App\Models\TelemetryLog;
use App\Models\MaintenanceLog;
use App\Models\ShiftDispatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleDetailController extends Controller
{
    public function show(HeavyVehicle $vehicle)
    {
        $telemetry = TelemetryLog::where('vehicle_id', $vehicle->id)
            ->orderByDesc('timestamp')
            ->take(100)
            ->get();

        $maintenance = MaintenanceLog::where('vehicle_id', $vehicle->id)
            ->latest()
            ->take(20)
            ->get();

        $latestTelemetry = $telemetry->first();

        $fuelTrend = TelemetryLog::where('vehicle_id', $vehicle->id)
            ->orderBy('timestamp')
            ->take(50)
            ->get(['timestamp', 'fuel_level_pct']);

        $dispatchHistory = ShiftDispatch::where('vehicle_id', $vehicle->id)
            ->with('operator')
            ->latest()
            ->take(20)
            ->get();

        return Inertia::render('Fleet/VehicleDetail', [
            'vehicle' => $vehicle,
            'telemetry' => $telemetry,
            'maintenance' => $maintenance,
            'latest_telemetry' => $latestTelemetry,
            'fuel_trend' => $fuelTrend,
            'dispatch_history' => $dispatchHistory,
        ]);
    }
}

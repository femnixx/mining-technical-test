<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeavyVehicle;
use App\Models\MaintenanceLog;
use App\Models\ShiftDispatch;
use Illuminate\Http\JsonResponse;

class FleetController extends Controller
{
    public function status(): JsonResponse
    {
        $fleet = HeavyVehicle::query();

        $active = (clone $fleet)->where('status', 'Active')->count();
        $idle = (clone $fleet)->where('status', 'Idle')->count();
        $maintenance = (clone $fleet)->where('status', 'Maintenance')->count();

        $avgFuel = HeavyVehicle::avg('fuel_capacity_l');

        $activeAlerts = MaintenanceLog::where('status', 'Open')->count();

        $activeShiftCount = ShiftDispatch::whereNull('shift_end')->count();

        return response()->json([
            'fleet' => [
                'total' => HeavyVehicle::count(),
                'active' => $active,
                'idle' => $idle,
                'maintenance' => $maintenance,
                'active_shifts' => $activeShiftCount,
            ],
            'average_fuel_capacity_l' => round($avgFuel, 2),
            'open_alerts' => $activeAlerts,
        ]);
    }
}

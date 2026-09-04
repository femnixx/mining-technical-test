<?php

namespace App\Http\Controllers;

use App\Models\HeavyVehicle;
use App\Models\MaintenanceLog;
use App\Models\ShiftDispatch;
use App\Models\TelemetryLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleetDashboardController extends Controller
{
    public function index()
    {
        $fleet = HeavyVehicle::all();

        $active = $fleet->where('status', 'Active');
        $idle = $fleet->where('status', 'Idle');
        $maintenance = $fleet->where('status', 'Maintenance');

        $activeShifts = ShiftDispatch::with(['vehicle', 'operator'])
            ->whereNull('shift_end')
            ->get();

        $currentShiftStart = now()->startOfDay();
        $totalTonnage = TelemetryLog::where('timestamp', '>=', $currentShiftStart)
            ->sum('load_tonnage');

        $alerts = MaintenanceLog::where('status', 'Open')
            ->with('vehicle')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Fleet/Dashboard', [
            'fleet_summary' => [
                'total' => $fleet->count(),
                'active' => $active->count(),
                'idle' => $idle->count(),
                'maintenance' => $maintenance->count(),
            ],
            'active_shifts' => $activeShifts,
            'total_tonnage_today' => round($totalTonnage, 2),
            'alerts' => $alerts,
            'vehicles' => $fleet,
        ]);
    }
}

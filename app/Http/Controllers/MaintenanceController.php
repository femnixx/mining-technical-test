<?php

namespace App\Http\Controllers;

use App\Models\HeavyVehicle;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaintenanceController extends Controller
{
    public function index()
    {
        $tickets = MaintenanceLog::with('vehicle')
            ->latest()
            ->paginate(20);

        $vehicles = HeavyVehicle::all();

        return Inertia::render('Fleet/MaintenanceQueue', [
            'tickets' => $tickets,
            'vehicles' => $vehicles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:heavy_vehicles,id'],
            'issue_description' => ['required', 'string', 'max:2000'],
            'priority' => ['required', 'in:Low,Medium,Critical'],
        ]);

        MaintenanceLog::create([
            'vehicle_id' => $validated['vehicle_id'],
            'reported_at' => now(),
            'issue_description' => $validated['issue_description'],
            'priority' => $validated['priority'],
            'status' => 'Open',
        ]);

        $vehicle = HeavyVehicle::find($validated['vehicle_id']);
        if ($vehicle) {
            $vehicle->update(['status' => 'Maintenance']);
        }

        return redirect()->route('maintenance.queue')->with('success', 'Maintenance ticket created.');
    }

    public function update(Request $request, MaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Open,In Progress,Resolved'],
            'resolved_at' => ['nullable', 'date'],
        ]);

        $maintenance->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'Resolved' ? now() : $validated['resolved_at'],
        ]);

        if ($validated['status'] === 'Resolved') {
            $maintenance->vehicle->update(['status' => 'Idle']);
        }

        return redirect()->route('maintenance.queue')->with('success', 'Maintenance ticket updated.');
    }
}

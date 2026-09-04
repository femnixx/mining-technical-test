<?php

namespace App\Http\Controllers;

use App\Models\HeavyVehicle;
use App\Models\Operator;
use App\Models\ShiftDispatch;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DispatchController extends Controller
{
    public function index()
    {
        $vehicles = HeavyVehicle::whereIn('status', ['Active', 'Idle'])->get();
        $operators = Operator::where('status', 'Off Duty')->get();

        $activeShifts = ShiftDispatch::with(['vehicle', 'operator'])
            ->whereNull('shift_end')
            ->get();

        return Inertia::render('Fleet/DispatchPanel', [
            'vehicles' => $vehicles,
            'operators' => $operators,
            'active_shifts' => $activeShifts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:heavy_vehicles,id'],
            'operator_id' => ['required', 'integer', 'exists:operators,id'],
            'pit_location' => ['required', 'string', 'max:255'],
            'target_tonnage' => ['nullable', 'numeric', 'min:0'],
        ]);

        $vehicle = HeavyVehicle::findOrFail($validated['vehicle_id']);
        $operator = Operator::findOrFail($validated['operator_id']);

        if ($operator->status === 'On Shift') {
            return back()->withErrors(['operator_id' => 'Operator is already on an active shift.']);
        }

        ShiftDispatch::create([
            'vehicle_id' => $vehicle->id,
            'operator_id' => $operator->id,
            'pit_location' => $validated['pit_location'],
            'shift_start' => now(),
            'target_tonnage' => $validated['target_tonnage'] ?? null,
        ]);

        $vehicle->update(['status' => 'Active']);
        $operator->update(['status' => 'On Shift']);

        return redirect()->route('dispatch.panel')->with('success', 'Dispatch created successfully.');
    }

    public function endShift(ShiftDispatch $dispatch)
    {
        $dispatch->update(['shift_end' => now()]);

        $dispatch->vehicle->update(['status' => 'Idle']);
        $dispatch->operator->update(['status' => 'Off Duty']);

        return redirect()->route('dispatch.panel')->with('success', 'Shift ended successfully.');
    }
}

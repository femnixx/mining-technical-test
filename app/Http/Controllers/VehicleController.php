<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Vehicle;
use App\Models\Booking;

class VehicleController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
            'booking_history' => Booking::where('vehicle_id', $vehicle->id)
                ->with(['user', 'approver1', 'approver2'])
                ->latest()
                ->get()
        ]);
    } 
}

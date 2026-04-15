<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BookingController extends Controller
{
    // add to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'required|string',
            'approver_1_id' => 'required|exists:users,id|different:approver_2_id',
            'approver_2_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        return DB::transaction(function () use ($validated) { 
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'vehicle_id' => $validated['vehicle_id'],
                'driver_name' => $validated['driver_name'],
                'approver_1_id' => $validated['approver_1_id'],
                'approver_2_id' => $validated['approver_2_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'pending'
            ]);

            ActivityLog::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'action' => 'Created',
                'description' => "New booking #{$booking->id} created for {$validated['driver_name']}",  
            ]);

            return redirect()->route('dashboard')->with('success', 'Booking submitted for approval!');

        });
    }

    public function create()
    {
        return Inertia::render('Bookings/Create', [
            'vehicles' => \App\Models\Vehicle::where('is_available', true)->get(),
            'approvers' => \App\Models\User::where('role', 'approver')->get(),
        ]);
    }
}

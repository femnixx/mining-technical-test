<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // add to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'required|string',
            'approver_1_id' => 'required|exists:users,id',
            'approver_2_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $booking = Booking::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]);
        
        ActivityLog::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'action' => 'Created',
            'description' => 'New booking created for ' . $validated['driver_name'],
        ])

        // return redirect()->route('bookings.index');
    }
}

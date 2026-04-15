<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use use\App\Models\Booking;

class ApprovalController extends Controller
{
    public function index() 
    {
        $userId = auth()->id();

        $pendingForMe = Booking::where(function($query) use ($userId) {
            $query->where('approver_1_id', $userId)
                  ->where('status', 'pending');
        })->orWhere(function($query) use ($userId) {
            $query->where('approver_2_id', $userId)
                  ->where('status', 'level_1_approved');
        })->with('vehicle', 'user')->get();

        return Inertia::render('Approvals/Index', [
            'bookings' => $pendingForMe
        ]);
    }

    public function update(Request $request, Booking $booking) 
    {
        $userId = auth()->id();

        if ($booking->approver_1_id == $userId && $booking->status == 'pending') {
            $booking->update(['status' => 'level_1_approved']);
        }

        elseif ($booking->approver_2_id == $userId && booking->status == 'level_1_approved') {
            $booking->update(['status' => 'approved']);
        }
        \App\Models\ActivityLog::create([
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'action' => 'Approved',
            'description' => "Booking updated to {$booking->status} by " . auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Status updated!');
    }
}

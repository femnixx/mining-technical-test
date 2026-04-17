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

    public function approve(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if ($booking->status === 'pending' && $user->id === $booking->approver_1_id) { 
            $booking->update(['status' => 'approved_level_1']);

            ActivityLog::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id, 
                'action' => 'Level 1 approval',
                'description' => "Approved by {$user->name}"
            ]);
            return back()->with('success', 'Level 1 approved');
        }
        if ($booking->status === 'approved_level_1' && $user->id === $booking->approver_2_id) {
            $booking->update(['status' => 'approved']);

            ActivityLog::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'action' => 'Final Approval',
                'description' => "Final approval given by {$user->name}"
            ]);
            return back()->with('success', 'Booking fully approved');
        }
        return abort(403, 'Unauthorized');

    }
    public function reject(Request $request, Booking $booking) 
    {
        $user = auth()->user();

        if (in_array($user->id, [$booking->approver_1_id, $booking->approver_2_id])) {
            $booking->update(['status' => 'rejected']);

            ActivityLog::create([
                'booking_id'=> $booking->id,
                'user_id' => $user->id,
                'action' => 'Rejected',
                'description' => "Booking rejected by {$user->name}"
            ]);
            return back()->with('error', 'Booking rejected');
        }
        return abort(403);
    }
    public function show(Booking $booking) { 
        return Inertia::render('Bookings/ShowVehicle', [
            'booking' => $booking->load(['vehicle', 'user', 'approver1', 'approver2']),
            'logs' => ActivityLog::where('booking_id', $booking->id)
                ->with('user')
                ->latest()
                ->get()
        ]);
    }
    public function index() 
    {
        return Inertia::render("Bookings/Index", [
            'bookings' => Booking::with(['vehicle', 'user'])
            ->latest()
            ->get(),
        ]);
    }
    public function export() 
    { 
        $bookings = Booking::with(['vehicle', 'adming', 'approver1', 'approver2'])
            ->latest()
            ->get();
        $fileName = 'laporan_pemesanan_kendaraan_' . date('Y-m-d') . '.csv';

       $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
        ];

        $columns = ['ID', 'Driver', 'Kendaraan', 'Plat', 'Admin', 'Approver 1', 'Approver 2', 'Status', 'Tanggal Mulai', 'Tanggal Selesai'];
        
        $callback = function() use($bookings, $columns) {
        $file = fopen('php://output', 'w');
        
        fputcsv($file, $columns);

        foreach ($bookings as $booking) {
            fputcsv($file, [
                $booking->id,
                $booking->driver_name,
                $booking->vehicle?->model_name,
                $booking->vehicle?->plate_number,
                $booking->admin?->name,
                $booking->approver1?->name,
                $booking->approver2?->name,
                $booking->status,
                $booking->start_date,
                $booking->end_date,
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}

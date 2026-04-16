<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get usage data for the chart (Grouped by Vehicle Model)
        $chartData = Booking::select('vehicles.model_name', DB::raw('count(*) as total'))
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->groupBy('vehicles.model_name')
            ->get();

        return Inertia::render('Dashboard', [
            'total_bookings'    => Booking::count(),
            'available_vehicles' => Vehicle::where('is_available', true)->count(),
            'recent_bookings'   => Booking::with(['vehicle', 'user'])->latest()->take(5)->get()->map(fn($b) => array_merge($b->toArray(), [
                'approver_1_id' => $b->approver_1_d,
                'approver_2_id' => $b->approver_2_id
            ])) ,
            'chart_data'        => $chartData
        ]);
    }
}
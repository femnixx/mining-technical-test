<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function store() 
    {

    }

    public function index()
    {
        return Inertia::render('Dashboard', [
            'total_bookings' =>Booking::count(),
            'available_vehicles' => Vehicle::where('is_available', true)->count(),
            'recent_bookings' => Booking::with('vehicle')->latest()->take(5)->get()
        ]);
    }

}

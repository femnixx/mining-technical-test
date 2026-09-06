<?php

use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FleetDashboardController;
use App\Http\Controllers\VehicleDetailController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('index');
    Route::get('/organization', [OnboardingController::class, 'organization'])->name('organization');
    Route::post('/organization', [OnboardingController::class, 'storeOrganization'])->name('organization.store');
    Route::get('/vehicles', [OnboardingController::class, 'vehicles'])->name('vehicles');
    Route::post('/vehicles', [OnboardingController::class, 'storeVehicles'])->name('vehicles.store');
    Route::get('/operators', [OnboardingController::class, 'operators'])->name('operators');
    Route::post('/operators', [OnboardingController::class, 'storeOperators'])->name('operators.store');
    Route::get('/complete', [OnboardingController::class, 'complete'])->name('complete');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'onboarding.complete'])
    ->name('dashboard');

Route::middleware(['auth', 'onboarding.complete'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');

    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

    Route::get('/fleet/dashboard', [FleetDashboardController::class, 'index'])->name('fleet.dashboard');
    Route::get('/fleet/vehicles/{vehicle}', [VehicleDetailController::class, 'show'])->name('fleet.vehicles.show');

    Route::get('/dispatch', [DispatchController::class, 'index'])->name('dispatch.panel');
    Route::post('/dispatch', [DispatchController::class, 'store'])->name('dispatch.store');
    Route::post('/dispatch/{dispatch}/end', [DispatchController::class, 'endShift'])->name('dispatch.end');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.queue');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenance.update');
});

Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'timestamp' => now()->toIso8601String(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database' => 'disconnected',
            'timestamp' => now()->toIso8601String(),
        ], 500);
    }
});

require __DIR__.'/auth.php';

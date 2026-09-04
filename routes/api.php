<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TelemetryController;
use App\Http\Controllers\Api\V1\FleetController;
use App\Http\Controllers\Api\V1\DispatchController;

Route::prefix('v1')->group(function () {
    Route::post('/telemetry', TelemetryController::class);
    Route::get('/fleet/status', [FleetController::class, 'status']);
    Route::post('/dispatches', DispatchController::class);
});

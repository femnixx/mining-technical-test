<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'industry', 'timezone', 'metadata'])]
class Organization extends Model
{
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function heavyVehicles(): HasMany
    {
        return $this->hasMany(HeavyVehicle::class);
    }

    public function operators(): HasMany
    {
        return $this->hasMany(Operator::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function telemetryLogs(): HasMany
    {
        return $this->hasMany(TelemetryLog::class);
    }

    public function shiftDispatches(): HasMany
    {
        return $this->hasMany(ShiftDispatch::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}

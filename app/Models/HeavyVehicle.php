<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['vehicle_code', 'type', 'model', 'status', 'fuel_capacity_l'])]
class HeavyVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'fuel_capacity_l' => 'decimal:2',
        ];
    }

    public function telemetryLogs()
    {
        return $this->hasMany(TelemetryLog::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function shiftDispatches()
    {
        return $this->hasMany(ShiftDispatch::class);
    }

    public function latestTelemetry()
    {
        return $this->hasOne(TelemetryLog::class)->latestOfMany();
    }
}

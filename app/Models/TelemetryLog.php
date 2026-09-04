<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['vehicle_id', 'timestamp', 'latitude', 'longitude', 'speed_kmh', 'fuel_level_pct', 'engine_temp_c', 'load_tonnage'])]
class TelemetryLog extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'speed_kmh' => 'decimal:1',
            'fuel_level_pct' => 'decimal:1',
            'engine_temp_c' => 'decimal:1',
            'load_tonnage' => 'decimal:2',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(HeavyVehicle::class);
    }
}

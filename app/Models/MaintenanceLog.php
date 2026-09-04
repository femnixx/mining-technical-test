<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['vehicle_id', 'reported_at', 'resolved_at', 'issue_description', 'priority', 'status'])]
class MaintenanceLog extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(HeavyVehicle::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['vehicle_id', 'operator_id', 'pit_location', 'shift_start', 'shift_end', 'target_tonnage', 'organization_id'])]
class ShiftDispatch extends Model
{
    use HasFactory;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected function casts(): array
    {
        return [
            'shift_start' => 'datetime',
            'shift_end' => 'datetime',
            'target_tonnage' => 'decimal:2',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(HeavyVehicle::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}

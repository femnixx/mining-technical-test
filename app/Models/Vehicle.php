<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[Fillable(['model_name', 'plate_number', 'type', 'ownership', 'location_id', 'fuel_consumption', 'last_service_at', 'is_available', 'distance_km'])]
class Vehicle extends Model
{
    use HasFactory;

    public function location() {
        return $this->belongsTo(Location::class);
    }
    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}

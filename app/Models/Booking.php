<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'vehicle_id',
    'approver_1_id',
    'approver_2_id',
    'driver_name',
    'start_date',
    'end_date',
    'status',
    'organization_id'
])]
class Booking extends Model
{
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function approver1() { 
        return $this->belongsTo(User::class, 'approver_1_id');
    }
    public function approver2() { 
        return $this->belongsTo(User::class, 'approver_2_id');
    }
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
    public function admin() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

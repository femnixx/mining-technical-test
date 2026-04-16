<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'vehicle_id',
    'approver_1_id',
    'approver_2_id',
    'driver_name',
    'start_date',
    'end_date',
    'status'
])]
class Booking extends Model
{
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
}

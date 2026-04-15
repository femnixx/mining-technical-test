<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['booking_id', 'user_id', 'action', 'description'])]
class ActivityLog extends Model
{
    public function user() 
    { 
        return $this->belongsTo(User::class);
    }
    public function booking() 
    { 
        return $this->belongsTo(Booking::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'user_id', 'action', 'description', 'organization_id'])]
class ActivityLog extends Model
{
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user() 
    { 
        return $this->belongsTo(User::class);
    }
    public function booking() 
    { 
        return $this->belongsTo(Booking::class);
    }
}

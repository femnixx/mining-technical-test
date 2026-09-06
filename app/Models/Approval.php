<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = ['organization_id'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

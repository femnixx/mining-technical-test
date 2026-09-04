<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'license_number', 'status'])]
class Operator extends Model
{
    use HasFactory, SoftDeletes;

    public function shiftDispatches()
    {
        return $this->hasMany(ShiftDispatch::class);
    }

    public function activeDispatch()
    {
        return $this->hasOne(ShiftDispatch::class)->whereNull('shift_end');
    }
}

<?php

namespace App\Models;

use App\Enums\ShiftStatus;
use Illuminate\Database\Eloquent\Model;

class AmbulanceShift extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'is_reserve',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'is_reserve' => 'boolean',
        'status' => ShiftStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

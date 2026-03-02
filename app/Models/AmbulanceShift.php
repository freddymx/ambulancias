<?php

namespace App\Models;

use App\Enums\ShiftStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class AmbulanceShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => ShiftStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isReserve(): bool
    {
        return $this->status === ShiftStatus::EnReserva;
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (AmbulanceShift $shift) {
            $date = $shift->date->toDateString();
            $status = $shift->status;

            $exists = self::whereDate('date', $date)
                ->where('status', $status)
                ->exists();

            if ($exists) {
                $type = $status === ShiftStatus::EnReserva ? 'de reserva' : 'regular';
                throw ValidationException::withMessages([
                    'date' => __("Ya existe un turno $type para esta fecha."),
                ]);
            }
        });

        static::updating(function (AmbulanceShift $shift) {
            $newStatus = $shift->status;
            $date = $shift->date->toDateString();

            $exists = self::whereDate('date', $date)
                ->where('status', $newStatus)
                ->where('id', '!=', $shift->id)
                ->exists();

            if ($exists) {
                $type = $newStatus === ShiftStatus::EnReserva ? 'de reserva' : 'regular';
                throw ValidationException::withMessages([
                    'date' => __("Ya existe un turno $type para esta fecha."),
                ]);
            }
        });
    }
}

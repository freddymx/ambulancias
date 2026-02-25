<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ShiftStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case EnReserva = 'en_reserva';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('app.shifts.status_pending'),
            self::Accepted => __('app.shifts.status_accepted'),
            self::Rejected => __('app.shifts.status_rejected'),
            self::EnReserva => __('app.shifts.status_en_reserva'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Accepted => 'success',
            self::Rejected => 'danger',
            self::EnReserva => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Accepted => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
            self::EnReserva => 'heroicon-o-user',
        };
    }
}

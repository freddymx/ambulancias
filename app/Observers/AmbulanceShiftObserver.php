<?php

namespace App\Observers;

use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use App\Notifications\AmbulanceShiftStatusChanged;

class AmbulanceShiftObserver
{
    public function updating(AmbulanceShift $ambulanceShift): void
    {
        if ($ambulanceShift->isDirty('status')) {
            $newStatus = $ambulanceShift->status;

            if (in_array($newStatus, [ShiftStatus::Accepted, ShiftStatus::Rejected])) {
                $ambulanceShift->user->notify(
                    new AmbulanceShiftStatusChanged(
                        status: $newStatus->value,
                        date: $ambulanceShift->date->format('d/m/Y')
                    )
                );
            }
        }
    }
}

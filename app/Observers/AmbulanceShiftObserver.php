<?php

namespace App\Observers;

use App\Models\AmbulanceShift;
use App\Models\User;
use App\Notifications\AmbulanceShiftUpdated;

class AmbulanceShiftObserver
{
    public function updated(AmbulanceShift $ambulanceShift): void
    {
        $changes = $ambulanceShift->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        // Send to affected user
        /** @var \App\Models\User $user */
        $user = $ambulanceShift->user;
        $user->notify(new AmbulanceShiftUpdated($ambulanceShift, $changes));

        // Send to admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            /** @var \App\Models\User $admin */
            // Avoid duplicate notification if the affected user is also an admin
            if ($admin->id !== $ambulanceShift->user_id) {
                $admin->notify(new AmbulanceShiftUpdated($ambulanceShift, $changes));
            }
        }
    }
}

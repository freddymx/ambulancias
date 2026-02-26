<?php

namespace App\Policies;

use App\Models\AmbulanceShift;
use App\Models\User;

class AmbulanceShiftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AmbulanceShift $ambulanceShift): bool
    {
        if (in_array($user->role, ['admin', 'gestor'])) {
            return true;
        }

        return $user->id === $ambulanceShift->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AmbulanceShift $ambulanceShift): bool
    {
        return in_array($user->role, ['admin', 'gestor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AmbulanceShift $ambulanceShift): bool
    {
        if (in_array($user->role, ['admin', 'gestor'])) {
            return true;
        }

        // Users can only delete their own pending or rejected shifts
        return $user->id === $ambulanceShift->user_id && in_array($ambulanceShift->status, [\App\Enums\ShiftStatus::Pending, \App\Enums\ShiftStatus::Rejected]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AmbulanceShift $ambulanceShift): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AmbulanceShift $ambulanceShift): bool
    {
        return $user->role === 'admin';
    }
}

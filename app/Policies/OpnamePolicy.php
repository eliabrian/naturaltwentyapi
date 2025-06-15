<?php

namespace App\Policies;

use App\Enums\OpnameStatus;
use App\Models\Opname;
use App\Models\User;

class OpnamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Opname $opname): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Opname $opname): bool
    {
        return ($user->isAdmin()) && ($opname->status->value !== OpnameStatus::Approved->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Opname $opname): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Opname $opname): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Opname $opname): bool
    {
        return $user->isAdmin();
    }
}

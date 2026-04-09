<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserLocation;

class UserLocationPolicy
{
    /**
     * Determine whether the user can update the location.
     */
    public function update(User $user, UserLocation $location): bool
    {
        return $user->id === $location->user_id;
    }

    /**
     * Determine whether the user can delete the location.
     */
    public function delete(User $user, UserLocation $location): bool
    {
        return $user->id === $location->user_id;
    }
}

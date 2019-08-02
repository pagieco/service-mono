<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnvironmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the environments.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('environment:list');
    }
}

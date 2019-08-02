<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the assets.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('asset:list');
    }
}

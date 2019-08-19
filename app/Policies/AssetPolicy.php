<?php

namespace App\Policies;

use App\User;
use App\Asset;
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

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User $user
     * @param  \App\Asset $asset
     * @return bool
     * @throws \Throwable
     */
    public function view(User $user, Asset $asset)
    {
        return $user->hasAccess('asset:view')
            && team()->assets->contains($asset->id);
    }
}

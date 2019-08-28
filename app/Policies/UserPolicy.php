<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can upload a new picture.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function uploadPicture(User $user)
    {
        return $user->hasAccess('user:upload-picture');
    }
}

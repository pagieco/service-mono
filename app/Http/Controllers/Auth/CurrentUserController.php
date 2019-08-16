<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;

class CurrentUserController
{
    /**
     * Get the current user.
     *
     * @return UserResource
     */
    public function __invoke(): UserResource
    {
        return new UserResource(request()->user());
    }
}

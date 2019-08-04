<?php

namespace App\Policies;

use App\User;
use App\Environment;
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

    /**
     * Determine whether the user can create a new environment.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasAccess('environment:create');
    }

    /**
     * Determine whether the user can view the environment.
     *
     * @param  \App\User $user
     * @param  \App\Environment $environment
     * @return bool
     * @throws \Throwable
     */
    public function view(User $user, Environment $environment)
    {
        return $user->hasAccess('environment:view')
            && team()->environments->contains($environment->id);
    }
}

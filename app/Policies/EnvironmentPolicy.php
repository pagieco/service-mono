<?php

namespace App\Policies;

use App\User;
use App\Domain;
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

    /**
     * Determine whether the user can attach the domain to the environment.
     *
     * @param  \App\User $user
     * @param  \App\Environment $environment
     * @param  \App\Domain $domain
     * @return bool
     * @throws \Throwable
     */
    public function attachDomain(User $user, Environment $environment, Domain $domain)
    {
        return $user->hasAccess('environment:attach-domain')
            && team()->environments->contains($environment->id)
            && team()->domains->contains($domain);
    }

    /**
     * Determine whether the user can detach the domain from the environment.
     *
     * @param  \App\User $user
     * @param  \App\Environment $environment
     * @param  \App\Domain $domain
     * @return bool
     * @throws \Throwable
     */
    public function detachDomain(User $user, Environment $environment, Domain $domain)
    {
        return $user->hasAccess('environment:detach-domain')
            && team()->environments->contains($environment->id)
            && team()->domains->contains($domain);
    }
}

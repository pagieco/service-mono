<?php

namespace App\Policies;

use App\User;
use App\Domain;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the domains.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('domain:list');
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User $user
     * @param  \App\Domain $domain
     * @return bool
     * @throws \Throwable
     */
    public function view(User $user, Domain $domain)
    {
        return $user->hasAccess('domain:view')
            && current_team()->domains->contains($domain->id);
    }
}

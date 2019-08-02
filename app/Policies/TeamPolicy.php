<?php

namespace App\Policies;

use App\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the domain.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->hasAccess('team:view')
            && $user->teams->pluck('id')->contains($team->id);
    }
}

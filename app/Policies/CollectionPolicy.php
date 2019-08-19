<?php

namespace App\Policies;

use App\User;
use App\Collection;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the collections.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('collection:list');
    }

    /**
     * Determine whether the user can create a new collection.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasAccess('collection:create');
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User $user
     * @param  \App\Collection $collection
     * @return bool
     * @throws \Throwable
     */
    public function view(User $user, Collection $collection)
    {
        return $user->hasAccess('collection:view')
            && current_team()->id === $collection->team_id;
    }
}

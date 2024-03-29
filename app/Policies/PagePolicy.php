<?php

namespace App\Policies;

use App\Page;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the pages.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('page:list');
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User $user
     * @param  \App\Page $page
     * @return bool
     * @throws \Throwable
     */
    public function publish(User $user, Page $page)
    {
        return $user->hasAccess('page:publish')
            && current_team()->pages->contains($page->id);
    }
}

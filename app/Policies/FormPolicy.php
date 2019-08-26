<?php

namespace App\Policies;

use App\Form;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the forms.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('form:list');
    }

    /**
     * Determine whether the user can create a new form.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasAccess('form:create');
    }

    public function viewSubmissions(User $user, Form $form)
    {
        return $user->hasAccess('form:view-submissions');
    }
}

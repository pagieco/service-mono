<?php

namespace App\Policies;

use App\User;
use App\Workflow;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkflowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the workflows.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasAccess('workflow:list');
    }

    /**
     * Determine whether the user can list the workflows.
     *
     * @param  \App\User $user
     * @param  \App\Workflow $workflow
     * @return bool
     * @throws \Throwable
     */
    public function view(User $user, Workflow $workflow)
    {
        return $user->hasAccess('workflow:view')
            && current_team()->workflows->contains($workflow->id);
    }

    /**
     * Determine whether the user can create a new workflow.
     *
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasAccess('workflow:create');
    }

    /**
     * Determine whether the user can update a workflow.
     *
     * @param  \App\User $user
     * @param  \App\Workflow $workflow
     * @return bool
     * @throws \Throwable
     */
    public function update(User $user, Workflow $workflow)
    {
        return $user->hasAccess('workflow:update')
            && current_team()->workflows->contains($workflow->id);
    }

    /**
     * Determine whether the user can delete a workflow.
     *
     * @param  \App\User $user
     * @param  \App\Workflow $workflow
     * @return bool
     * @throws \Throwable
     */
    public function delete(User $user, Workflow $workflow)
    {
        return $user->hasAccess('workflow:delete')
            && current_team()->workflows->contains($workflow->id);
    }
}

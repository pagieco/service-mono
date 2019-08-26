<?php

namespace App\Http\Controllers\Workflow;

use App\Workflow;
use App\Http\Resources\WorkflowResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetWorkflowController
{
    use AuthorizesRequests;

    /**
     * Get the given workflow.
     *
     * @param  \App\Workflow $workflow
     * @return \App\Http\Resources\WorkflowResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Workflow $workflow): WorkflowResource
    {
        $this->authorize('view', $workflow);

        return new WorkflowResource($workflow);
    }
}

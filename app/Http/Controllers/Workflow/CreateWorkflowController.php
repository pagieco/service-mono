<?php

namespace App\Http\Controllers\Workflow;

use App\Domain;
use App\Workflow;
use App\Http\Resources\WorkflowResource;
use App\Http\Requests\CreateWorkflowRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateWorkflowController
{
    use AuthorizesRequests;

    /**
     * Create a new workflow.
     *
     * @param  \App\Http\Requests\CreateWorkflowRequest $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\WorkflowResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(CreateWorkflowRequest $request, Domain $domain): WorkflowResource
    {
        $this->authorize('create', Workflow::class);

        $workflow = $this->createWorkflowFromRequest($request);

        return new WorkflowResource($workflow);
    }

    /**
     * Create a new workflow from the request.
     *
     * @param  \App\Http\Requests\CreateWorkflowRequest $request
     * @return \App\Workflow
     * @throws \Throwable
     */
    protected function createWorkflowFromRequest(CreateWorkflowRequest $request): Workflow
    {
        $workflow = new Workflow($request->all());

        $workflow->team()->associate(current_team());

        return tap($workflow)->save();
    }
}

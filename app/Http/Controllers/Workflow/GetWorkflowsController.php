<?php

namespace App\Http\Controllers\Workflow;

use App\Workflow;
use App\Http\Response;
use App\Http\Resources\WorkflowsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetWorkflowsController
{
    use AuthorizesRequests;

    /**
     * Get a list of workflows.
     *
     * @return \App\Http\Resources\WorkflowsResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(): WorkflowsResource
    {
        $this->authorize('list', Workflow::class);

        $workflows = current_team()->workflows()->paginate();

        abort_if($workflows->isEmpty(), Response::HTTP_NO_CONTENT);

        return new WorkflowsResource($workflows);
    }
}

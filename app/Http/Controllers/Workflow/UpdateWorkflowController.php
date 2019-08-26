<?php

namespace App\Http\Controllers\Workflow;

use App\Domain;
use App\Workflow;
use App\Http\Resources\WorkflowResource;
use App\Http\Requests\UpdateWorkflowRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateWorkflowController
{
    use AuthorizesRequests;

    public function __invoke(UpdateWorkflowRequest $request, Domain $domain, Workflow $workflow): WorkflowResource
    {
        $this->authorize('update', $workflow);

        return new WorkflowResource(
            tap($workflow)->update($request->all())
        );
    }
}

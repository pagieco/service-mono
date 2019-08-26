<?php

namespace App\Http\Controllers\Workflow;

use App\Workflow;
use App\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteWorkflowController
{
    use AuthorizesRequests;

    /**
     * Delete the given workflow.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Workflow $workflow
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Workflow $workflow)
    {
        $this->authorize('delete', $workflow);

        $workflow->delete();

        abort(Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Environment;

use App\Environment;
use App\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteEnvironmentController
{
    use AuthorizesRequests;

    /**
     * Delete the given environment.
     *
     * @param  \App\Environment $environment
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Environment $environment)
    {
        $this->authorize('delete', $environment);

        $environment->delete();

        abort(Response::HTTP_NO_CONTENT);
    }
}

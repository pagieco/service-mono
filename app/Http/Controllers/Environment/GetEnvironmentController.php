<?php

namespace App\Http\Controllers\Environment;

use App\Environment;
use App\Http\Resources\EnvironmentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetEnvironmentController
{
    use AuthorizesRequests;

    /**
     * Get a specific environment.
     *
     * @param  \App\Environment $environment
     * @return \App\Http\Resources\EnvironmentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Environment $environment): EnvironmentResource
    {
        $this->authorize('view', $environment);

        return new EnvironmentResource($environment);
    }
}

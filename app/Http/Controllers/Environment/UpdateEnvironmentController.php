<?php

namespace App\Http\Controllers\Environment;

use App\Environment;
use App\Http\Resources\EnvironmentResource;
use App\Http\Requests\UpdateEnvironmentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateEnvironmentController
{
    use AuthorizesRequests;

    /**
     * Update the given environment.
     *
     * @param  \App\Http\Requests\UpdateEnvironmentRequest $request
     * @param  \App\Environment $environment
     * @return \App\Http\Resources\EnvironmentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(UpdateEnvironmentRequest $request, Environment $environment)
    {
        $this->authorize('update', $environment);

        return new EnvironmentResource(
            tap($environment)->update($request->all())
        );
    }
}

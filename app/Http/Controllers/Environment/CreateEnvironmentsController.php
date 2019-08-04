<?php

namespace App\Http\Controllers\Environment;

use App\Environment;
use App\Http\Resources\EnvironmentResource;
use App\Http\Requests\CreateEnvironmentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateEnvironmentsController
{
    use AuthorizesRequests;

    public function __invoke(CreateEnvironmentRequest $request)
    {
        $this->authorize('create', Environment::class);

        $environment = $this->createEnvironmentFromRequest($request);

        return new EnvironmentResource($environment);
    }

    protected function createEnvironmentFromRequest(CreateEnvironmentRequest $request)
    {
        $environment = new Environment($request->all());

        $environment->team()->associate(team());

        return tap($environment)->save();
    }
}

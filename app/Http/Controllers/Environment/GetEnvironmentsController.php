<?php

namespace App\Http\Controllers\Environment;

use App\Environment;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\EnvironmentsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetEnvironmentsController
{
    use AuthorizesRequests;

    /**
     * Get a list of environments.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\EnvironmentsResource
     * @throws \Throwable
     */
    public function __invoke(Request $request): EnvironmentsResource
    {
        $this->authorize('list', Environment::class);

        $environments = current_team()->environments;

        abort_if($environments->isEmpty(), Response::HTTP_NO_CONTENT);

        return new EnvironmentsResource($environments);
    }
}

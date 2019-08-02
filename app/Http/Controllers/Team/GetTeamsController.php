<?php

namespace App\Http\Controllers\Team;

use App\Team;
use App\Http\Resources\TeamsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetTeamsController
{
    use AuthorizesRequests;

    /**
     * Get a list of teams the user has access to.
     *
     * @return \App\Http\Resources\TeamsResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(): TeamsResource
    {
        $this->authorize('list', Team::class);

        $teams = auth()->user()->teams;

        return new TeamsResource($teams);
    }
}

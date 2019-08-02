<?php

namespace App\Http\Controllers\Team;

use App\Team;
use App\Http\Resources\TeamResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetTeamController
{
    use AuthorizesRequests;

    /**
     * Get a specific team.
     *
     * @param  \App\Team $team
     * @return \App\Http\Resources\TeamResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Team $team): TeamResource
    {
        $this->authorize('view', $team);

        return new TeamResource($team);
    }
}

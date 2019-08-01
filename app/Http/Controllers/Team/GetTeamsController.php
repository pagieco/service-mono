<?php

namespace App\Http\Controllers\Team;

use App\Http\Resources\TeamsResource;

class GetTeamsController
{
    /**
     * Get a list of teams the user has access to.
     *
     * @return \App\Http\Resources\TeamsResource
     */
    public function __invoke(): TeamsResource
    {
        $teams = auth()->user()->teams;

        return new TeamsResource($teams);
    }
}

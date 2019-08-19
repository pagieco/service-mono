<?php

namespace App\Http\Controllers\Domain;

use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\DomainsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetDomainsController
{
    use AuthorizesRequests;

    /**
     * Get a list of domains.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\DomainsResource
     * @throws \Throwable
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request): DomainsResource
    {
        $this->authorize('list', Domain::class);

        $domains = current_team()->domains;

        abort_if($domains->isEmpty(), Response::HTTP_NO_CONTENT);

        return new DomainsResource($domains);
    }
}

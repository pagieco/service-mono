<?php

namespace App\Http\Controllers\Domain;

use App\Domain;
use App\Http\Resources\DomainResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetDomainController
{
    use AuthorizesRequests;

    /**
     * Get the specific domain.
     *
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\DomainResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain): DomainResource
    {
        $this->authorize('view', $domain);

        return new DomainResource($domain);
    }
}

<?php

namespace App\Http\Controllers\Domain;

use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\DomainsResource;

class GetDomainsController
{
    /**
     * Get a list of domains.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\DomainsResource
     * @throws \Throwable
     */
    public function __invoke(Request $request): DomainsResource
    {
        $domains = team()->domains;

        abort_if($domains->isEmpty(), Response::HTTP_NO_CONTENT);

        return new DomainsResource($domains);
    }
}

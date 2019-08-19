<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\Http\Resources\CollectionResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetCollectionController
{
    use AuthorizesRequests;

    /**
     * Get the given collection.
     *
     * @param  \App\Domain $domain
     * @param  \App\Collection $collection
     * @return \App\Http\Resources\CollectionResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain, Collection $collection): CollectionResource
    {
        $this->authorize('view', $collection);

        return new CollectionResource($collection);
    }
}

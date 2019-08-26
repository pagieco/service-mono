<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\Http\Response;
use App\Http\Resources\CollectionEntriesResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetCollectionEntriesController
{
    use AuthorizesRequests;

    /**
     * Get a list of collection entries.
     *
     * @param  \App\Domain $domain
     * @param  \App\Collection $collection
     * @return \App\Http\Resources\CollectionEntriesResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain, Collection $collection): CollectionEntriesResource
    {
        $this->authorize('list-entries', $collection);

        $entries = $collection->entries;

        abort_if($entries->isEmpty(), Response::HTTP_NO_CONTENT);

        return new CollectionEntriesResource($entries);
    }
}

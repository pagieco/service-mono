<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteCollectionController
{
    use AuthorizesRequests;

    /**
     * Delete the given collection.
     *
     * @param  \App\Domain $domain
     * @param  \App\Collection $collection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain, Collection $collection)
    {
        $this->authorize('delete', $collection);

        $collection->delete();

        abort(Response::HTTP_NO_CONTENT);
    }
}

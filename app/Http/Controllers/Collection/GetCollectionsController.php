<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\Http\Response;
use App\Http\Resources\CollectionsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetCollectionsController
{
    use AuthorizesRequests;

    /**
     * Get a list of collections.
     *
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\CollectionsResource
     * @throws \Throwable
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain): CollectionsResource
    {
        $this->authorize('list', Collection::class);

        $collections = Collection::query()
            ->where('team_id', current_team()->id)
            ->where('domain_id', $domain->id)
            ->paginate();

        abort_if($collections->isEmpty(), Response::HTTP_NO_CONTENT);

        return new CollectionsResource($collections);
    }
}

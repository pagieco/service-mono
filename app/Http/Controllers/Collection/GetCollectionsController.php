<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\Http\Response;
use App\Http\Resources\CollectionsResource;

class GetCollectionsController
{
    /**
     * Get a list of collections.
     *
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\CollectionsResource
     * @throws \Throwable
     */
    public function __invoke(Domain $domain): CollectionsResource
    {
        $collections = Collection::query()
            ->where('team_id', team()->id)
            ->where('domain_id', $domain->id)
            ->get();

        abort_if($collections->isEmpty(), Response::HTTP_NO_CONTENT);

        return new CollectionsResource($collections);
    }
}

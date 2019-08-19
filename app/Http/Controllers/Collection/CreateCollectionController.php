<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use Illuminate\Http\Request;
use App\Http\Resources\CollectionResource;
use App\Http\Requests\CreateCollectionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateCollectionController
{
    use AuthorizesRequests;

    /**
     * Handle the create collection request.
     *
     * @param  \App\Http\Requests\CreateCollectionRequest $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\CollectionResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(CreateCollectionRequest $request, Domain $domain): CollectionResource
    {
        $this->authorize('create', Collection::class);

        $collection = $this->createCollection($request, $domain);

        return new CollectionResource($collection);
    }

    /**
     * Create a new collection instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Collection
     * @throws \Throwable
     */
    protected function createCollection(Request $request, Domain $domain): Collection
    {
        $collection = new Collection($request->all());

        $collection->domain()->associate($domain);
        $collection->team()->associate(current_team());

        return tap($collection)->save();
    }
}

<?php

namespace App\Http\Controllers\Collection;

use App\Domain;
use App\Collection;
use App\CollectionField;
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

        foreach ($request->fields as $field) {
            $this->createCollectionField($collection, $field);
        }

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

    /**
     * Create a new collection field.
     *
     * @param  \App\Collection $collection
     * @param  array $field
     * @return \App\CollectionField
     */
    protected function createCollectionField(Collection $collection, array $field): CollectionField
    {
        return $collection->fields()->create($field);
    }
}

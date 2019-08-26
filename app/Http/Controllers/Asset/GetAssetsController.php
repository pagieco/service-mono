<?php

namespace App\Http\Controllers\Asset;

use App\Asset;
use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\AssetsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetAssetsController
{
    use AuthorizesRequests;

    /**
     * Get a list of assets.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\AssetsResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Domain $domain): AssetsResource
    {
        $this->authorize('list', Asset::class);

        $assets = $domain->assets()->paginate();

        abort_if($assets->isEmpty(), Response::HTTP_NO_CONTENT);

        return new AssetsResource($assets);
    }
}

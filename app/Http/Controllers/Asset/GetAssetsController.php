<?php

namespace App\Http\Controllers\Asset;

use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\AssetsResource;

class GetAssetsController
{
    /**
     * Get a list of assets.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\AssetsResource
     */
    public function __invoke(Request $request, Domain $domain): AssetsResource
    {
        $assets = $domain->assets;

        abort_if($assets->isEmpty(), Response::HTTP_NO_CONTENT);

        return new AssetsResource($assets);
    }
}

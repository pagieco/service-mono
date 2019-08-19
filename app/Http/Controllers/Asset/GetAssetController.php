<?php

namespace App\Http\Controllers\Asset;

use App\Asset;
use App\Domain;
use Illuminate\Http\Request;
use App\Http\Resources\AssetResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetAssetController
{
    use AuthorizesRequests;

    /**
     * Get the given asset.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @param  \App\Asset $asset
     * @return \App\Http\Resources\AssetResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Domain $domain, Asset $asset)
    {
        $this->authorize('view', $asset);

        return new AssetResource($asset);
    }
}

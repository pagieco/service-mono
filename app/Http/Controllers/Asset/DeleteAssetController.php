<?php

namespace App\Http\Controllers\Asset;

use App\Asset;
use App\Domain;
use App\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteAssetController
{
    use AuthorizesRequests;

    public function __invoke(Domain $domain, Asset $asset)
    {
        $this->authorize('delete', $asset);

        $asset->unlink();

        abort(Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Asset;

use App\Asset;
use App\Http\Resources\AssetResource;
use App\Http\Requests\UploadAssetRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UploadAssetController
{
    use AuthorizesRequests;

    public function __invoke(UploadAssetRequest $request)
    {
        $this->authorize('upload', Asset::class);

        $asset = tap(Asset::fromFile($request->file('file'))->upload())->save();

        return new AssetResource($asset);
    }
}

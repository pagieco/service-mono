<?php

namespace App\Http\Controllers\Asset;

use App\Asset;
use Illuminate\Http\Request;
use App\Jobs\CreateAssetThumbnail;
use App\Http\Resources\AssetResource;
use App\Http\Requests\UploadAssetRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UploadAssetController
{
    use DispatchesJobs;
    use AuthorizesRequests;

    /**
     * Upload the given asset.
     *
     * @param  \App\Http\Requests\UploadAssetRequest $request
     * @return \App\Http\Resources\AssetResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(UploadAssetRequest $request): AssetResource
    {
        $this->authorize('upload', Asset::class);

        $asset = $this->upload($request);

        return new AssetResource($asset);
    }

    /**
     * Upload the asset and dispatch the create thumbnail job.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Asset
     * @throws \Throwable
     */
    protected function upload(Request $request): Asset
    {
        $file = $request->file('file');

        $asset = tap(Asset::fromFile($file)->upload())->save();

        $this->dispatch(new CreateAssetThumbnail($asset));

        return $asset;
    }
}

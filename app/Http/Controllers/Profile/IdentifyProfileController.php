<?php

namespace App\Http\Controllers\Profile;

use App\Domain;
use App\Profile;
use App\Http\Response;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\IdentifyProfileRequest;

class IdentifyProfileController
{
    /**
     * Identify the requested profile by the given email or
     *
     * @param  \App\Http\Requests\IdentifyProfileRequest $request
     * @return \App\Http\Resources\ProfileResource
     */
    public function __invoke(IdentifyProfileRequest $request): ProfileResource
    {
        $domain = Domain::query()
            ->where('api_token', $request->getDomainToken())
            ->firstOrFail();

        abort_if(! $profile = Profile::identify($request, $domain), Response::HTTP_NOT_FOUND);

        return new ProfileResource($profile);
    }
}

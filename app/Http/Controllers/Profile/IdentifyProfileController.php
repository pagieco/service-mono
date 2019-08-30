<?php

namespace App\Http\Controllers\Profile;

use App\Domain;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\IdentifyProfileRequest;
use Illuminate\Support\Facades\Cookie;

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

        // If the request has the profile-id posted then
        // just use that value to retrieve the profile.
        if ($request->has('profile_id')) {
            $profile = $domain->profiles()->findOrFail($request->get('profile_id'));
        } else if ($request->has('email')) {
            // Othberwise, when the email is posted we can
            // retrieve the profile from the given email address
            $profile = $domain->profiles()->where([
                'email' => $request->get('email'),
            ])->firstOrFail();
        }

        return new ProfileResource($profile);
    }
}

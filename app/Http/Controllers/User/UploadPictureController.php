<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Requests\UploadPictureRequest;
use App\Http\Resources\ProfilePictureResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UploadPictureController
{
    use AuthorizesRequests;

    /**
     * Upload a new profile picture for the current user.
     *
     * @param  \App\Http\Requests\UploadPictureRequest $request
     * @return \App\Http\Resources\ProfilePictureResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(UploadPictureRequest $request): ProfilePictureResource
    {
        $this->authorize('upload-picture', User::class);

        $user = $request->user();

        $user->update([
            'picture' => $user->uploadProfilePicture($request->file('picture'))
        ]);

        return new ProfilePictureResource($user);
    }
}

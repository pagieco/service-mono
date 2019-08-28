<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;

class UpdateUserController
{
    /**
     * Update the current user.
     *
     * @param  \App\Http\Requests\UpdateUserRequest $request
     * @return \App\Http\Resources\UserResource
     */
    public function __invoke(UpdateUserRequest $request): UserResource
    {
        $user = auth()->user();

        $payload = $request->all();

        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $user->update($payload);

        return new UserResource($user);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegistrationRequest;

class RegistrationController
{
    public function __invoke(RegistrationRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return new UserResource($user);
    }
}

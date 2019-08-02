<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * Verifiy the user through a signed-url.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->id);

        abort_if($user->hasVerifiedEmail(), Response::HTTP_BAD_REQUEST, 'The user was already verified.');

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        abort(Response::HTTP_CREATED, 'The user is successfully verified.');
    }
}

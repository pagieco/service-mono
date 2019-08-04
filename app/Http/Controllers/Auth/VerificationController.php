<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Notifications\WelcomeMessage;

class VerificationController extends Controller
{
    /**
     * Verifiy the user through a signed-url.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->id);

        abort_if($user->hasVerifiedEmail(), Response::HTTP_BAD_REQUEST, 'The user was already verified.');

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->notify(new WelcomeMessage);

        return redirect(config('auth.redirect-after-verify'));
    }
}

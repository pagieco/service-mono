<?php

namespace App\Http\Controllers\Auth;

use App\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests\AuthenticateRequest;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthenticateController
{
    use ThrottlesLogins;

    /**
     * Proxy the login request to the oauth token endpoint. The request is proxied because
     * we need to keep the client_id and the client_secret a secret to other users.
     *
     * @param  \App\Http\Requests\AuthenticateRequest $request
     * @param  \GuzzleHttp\Client $client
     * @return \Illuminate\Http\JsonResponse|\Psr\Http\Message\StreamInterface
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(AuthenticateRequest $request, Client $client)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        try {
            return $this->attemptLogin($request, $client);
        } catch (BadResponseException $exception) {
            return $this->sendBadRequestResponse($exception);
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \GuzzleHttp\Client $client
     * @return mixed
     */
    protected function attemptLogin(Request $request, Client $client)
    {
        $endpoint = config('services.passport.endpoint');
        $clientId = config('services.passport.client.id');
        $clientSecret = config('services.passport.client.secret');

        return $client->post($endpoint, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $request->email,
                'password' => $request->password,
            ],
        ])->getBody();
    }

    /**
     * Send a bad request response.
     *
     * @param  \GuzzleHttp\Exception\BadResponseException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendBadRequestResponse(BadResponseException $exception)
    {
        return Response::jsonStatus($exception->getCode());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }
}

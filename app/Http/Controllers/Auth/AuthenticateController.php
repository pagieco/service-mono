<?php

namespace App\Http\Controllers\Auth;

use App\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests\AuthenticateRequest;
use Illuminate\Auth\AuthenticationException;
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
     * @throws \Throwable
     */
    public function __invoke(AuthenticateRequest $request, Client $client)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        try {
            return $this->attemptLogin($request, $client);
        } catch (\Exception $exception) {
            return $this->sendBadRequestResponse($exception);
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \GuzzleHttp\Client $client
     * @return mixed
     * @throws \Throwable
     */
    protected function attemptLogin(Request $request, Client $client)
    {
        $endpoint = config('services.passport.endpoint');
        $clientId = config('services.passport.client.id');
        $clientSecret = config('services.passport.client.secret');

        $response = $client->post($endpoint, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $request->email,
                'password' => $request->password,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new AuthenticationException;
        }

        return $response->getBody();
    }

    /**
     * Send a bad request response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendBadRequestResponse()
    {
        return Response::jsonStatus(Response::HTTP_UNAUTHORIZED);
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

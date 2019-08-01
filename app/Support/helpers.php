<?php

use App\Team;
use App\Http\Response;
use Faker\Factory as Faker;
use Faker\Generator as FakerGenerator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;

if (! function_exists('created')) {
    /**
     * Return a created response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function created()
    {
        return status_response(Response::HTTP_CREATED);
    }
}

if (! function_exists('current_domain')) {
    function current_domain()
    {

    }
}

if (! function_exists('faker')) {
    /**
     * Create a new faker instance.
     *
     * @param  string $locale
     * @return \Faker\Generator
     */
    function faker($locale = Faker::DEFAULT_LOCALE): FakerGenerator
    {
        return Faker::create($locale);
    }
}

if (! function_exists('ok')) {
    /**
     * Return an OK response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function ok()
    {
        return status_response(Response::HTTP_OK);
    }
}

if (! function_exists('pretty_bytes')) {
    /**
     * Pretty format the given filesize.
     *
     * @param  int $filesize
     * @return string
     */
    function pretty_bytes(int $filesize): string
    {
        $bytes = log($filesize, 1024);

        $suffix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'][(int) floor($bytes)];

        return round(pow(1024, $bytes - floor($bytes)), 2).' '.$suffix;
    }
}

if (! function_exists('response_message')) {
    /**
     * Get the status response message.
     *
     * @param  int $status
     * @return string
     */
    function response_message(int $status): string
    {
        return Response::$statusTexts[$status];
    }
}

if (! function_exists('status_response')) {
    /**
     * Create a status response.
     *
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function status_response(int $status): Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => response_message($status),
        ], $status);
    }
}

if (! function_exists('team')) {
    /**
     * Get the current team if the user is logged-in.
     *
     * @return \App\Team
     * @throws \Throwable
     */
    function team(): Team
    {
        return user()->currentTeam();
    }
}

if (! function_exists('user')) {
    /**
     * Get the currently logged in user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \Throwable
     */
    function user(): ?Authenticatable
    {
        throw_if(! $user = auth()->user(), AuthenticationException::class);

        return $user;
    }
}

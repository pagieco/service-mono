<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\ResponseFactory;

class Response extends \Illuminate\Http\Response
{
    /**
     * Return a created response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function created(): JsonResponse
    {
        return static::jsonStatus(static::HTTP_CREATED);
    }

    /**
     * Return an OK response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ok(): JsonResponse
    {
        return static::jsonStatus(static::HTTP_OK);
    }

    /**
     * Get the status response message.
     *
     * @param  int $status
     * @return string
     */
    public static function message(int $status): string
    {
        return static::$statusTexts[$status];
    }

    /**
     * Create a status response.
     *
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonStatus(int $status): JsonResponse
    {
        return app(ResponseFactory::class)->json([
            'message' => static::message($status),
        ], $status);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use App\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception): JsonResponse
    {
        if ($this->isModelNotFoundException($exception)) {
            return Response::jsonStatus(Response::HTTP_NOT_FOUND);
        }

        if ($this->isHttpException($exception)) {
            $statusCode = method_exists($exception, 'getStatusCode')
                ? $exception->getStatusCode()
                : Response::HTTP_NOT_FOUND;

            if ($message = $exception->getMessage()) {
                return response()->json([
                    'message' => $message,
                ], $statusCode);
            }

            return Response::jsonStatus($statusCode);
        }

        if ($this->isAuthenticationException($exception)) {
            return Response::jsonStatus(Response::HTTP_UNAUTHORIZED);
        }

        if ($this->isAuthorizationException($exception)) {
            return Response::jsonStatus(Response::HTTP_FORBIDDEN);
        }

        if ($this->isValidationException($exception)) {
            return $this->invalidJson($request, $exception);
        }

        return Response::jsonStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Determines if the given exception is an http exception.
     *
     * @param  Exception $exception
     * @return bool
     */
    protected function isHttpException(Exception $exception): bool
    {
        return $exception instanceof HttpException
            || $exception instanceof NotFoundHttpException;
    }

    /**
     * Determines if the given exception is an authentication exception.
     *
     * @param  Exception $exception
     * @return bool
     */
    protected function isAuthenticationException(Exception $exception): bool
    {
        return $exception instanceof AuthenticationException;
    }

    /**
     * Determines if the given exception is an authorization exception.
     *
     * @param  Exception $exception
     * @return bool
     */
    protected function isAuthorizationException(Exception $exception): bool
    {
        return $exception instanceof AuthorizationException;
    }

    /**
     * Determines if the given exception is an Eloquent model not found exception.
     *
     * @param  Exception $exception
     * @return bool
     */
    protected function isModelNotFoundException(Exception $exception): bool
    {
        return $exception instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is a validation exception.
     *
     * @param  Exception $exception
     * @return bool
     */
    protected function isValidationException(Exception $exception): bool
    {
        return $exception instanceof ValidationException;
    }
}

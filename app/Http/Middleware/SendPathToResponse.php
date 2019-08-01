<?php

namespace App\Http\Middleware;

use Closure;

class SendPathToResponse
{
    /**
     * Send the reqwuest path as a header to the response.
     * This is used only in testing to test the open-api response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (app()->runningUnitTests()) {
            $response->headers->set('x-origin-path', $request->path());
        }

        return $response;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Router\RouteResolver;

class FrontendController
{
    public function __invoke(Request $request)
    {
        $resolver = new RouteResolver($request);

        $domain = $resolver->domain();

        return $resolver->resource($domain)->toResponse($request);
    }
}

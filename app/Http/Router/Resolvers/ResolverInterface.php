<?php

namespace App\Http\Router\Resolvers;

use App\Domain;
use Illuminate\Http\Request;

interface ResolverInterface
{
    /**
     * Try to resolve the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain|null $domain
     * @return mixed
     */
    public static function resolve(Request $request, Domain $domain = null);
}

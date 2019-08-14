<?php

namespace App\Http\Router\Resolvers;

use App\Domain;
use Illuminate\Http\Request;

class DomainResolver implements ResolverInterface
{
    /**
     * Try to resolve the current domain.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain|null $domain
     * @return mixed
     */
    public static function resolve(Request $request, Domain $domain = null)
    {
        return Domain::where('domain_name', $request->getHost())->firstOrFail();
    }
}

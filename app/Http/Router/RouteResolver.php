<?php

namespace App\Http\Router;

use App\Domain;
use App\Http\Response;
use App\Services\Pagie;
use Illuminate\Http\Request;
use App\Http\Router\Resolvers\PageResolver;
use App\Http\Router\Resolvers\DomainResolver;
use Illuminate\Contracts\Support\Responsable;

class RouteResolver
{
    /**
     * The list of resolvers.
     *
     * @var array
     */
    protected $resolvers = [
        PageResolver::class,
    ];

    /**
     * The current request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * Create a new request resolver instance.
     *
     * @param  Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Resolve the current domain.
     *
     * @return Domain|null
     */
    public function domain(): ?Domain
    {
        $domain = DomainResolver::resolve($this->request);

        Pagie::getInstance()->setResolvedDomain($domain);

        return $domain;
    }

    /**
     * Resolve the request.
     *
     * @param  Domain $domain
     * @return Responsable|null
     */
    public function resource(Domain $domain): ?Responsable
    {
        $resource = $this->resolves($domain, $this->request);

        Pagie::getInstance()->setResolvedResource($resource);

        return $resource;
    }

    /**
     * Determine that the current request matches one of the resources.
     * When nothing matches, a 404 response will be thrown. Otherwise
     * the matched record will be returned for further dispatching.
     *
     * @param  Domain $domain
     * @param  Request $request
     * @return Responsable|null
     */
    protected function resolves(Domain $domain, Request $request): ?Responsable
    {
        foreach ($this->resolvers as $resource => $resolver) {
            if (false === ($match = $resolver::resolve($request, $domain))) {
                continue;
            }

            return $match;
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}

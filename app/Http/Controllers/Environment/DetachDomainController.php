<?php

namespace App\Http\Controllers\Environment;

use App\Domain;
use App\Environment;
use App\Http\Response;
use App\Http\Requests\DetachDomainFromEnvironmentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DetachDomainController
{
    use AuthorizesRequests;

    /**
     * Detach the given domain from the given environment.
     *
     * @param  \App\Http\Requests\DetachDomainFromEnvironmentRequest $request
     * @param  \App\Environment $environment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(DetachDomainFromEnvironmentRequest $request, Environment $environment)
    {
        $domain = team()->domains()->find($request->domain);

        abort_unless($domain, Response::HTTP_BAD_REQUEST);

        $this->authorize('detach-domain', [$environment, $domain]);

        $this->detach($domain, $environment);

        return Response::jsonStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Detach a domain from an environment.
     *
     * @param  \App\Domain $domain
     * @param  \App\Environment $environment
     */
    protected function detach(Domain $domain, Environment $environment): void
    {
        $domain->environment()->dissociate($environment);

        $domain->save();
    }
}

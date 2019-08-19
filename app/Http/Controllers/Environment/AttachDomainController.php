<?php

namespace App\Http\Controllers\Environment;

use App\Domain;
use App\Environment;
use App\Http\Response;
use App\Http\Requests\AttachDomainToEnvironmentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttachDomainController
{
    use AuthorizesRequests;

    /**
     * Attach the given domain to the given environment.
     *
     * @param  \App\Http\Requests\AttachDomainToEnvironmentRequest $request
     * @param  \App\Environment $environment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(AttachDomainToEnvironmentRequest $request, Environment $environment)
    {
        $domain = current_team()->domains()->find($request->domain);

        abort_unless($domain, Response::HTTP_BAD_REQUEST);

        $this->authorize('attach-domain', [$environment, $domain]);

        $this->attach($domain, $environment);

        return created();
    }

    /**
     * Attach a domain to an environment.
     *
     * @param  \App\Domain $domain
     * @param  \App\Environment $environment
     */
    protected function attach(Domain $domain, Environment $environment): void
    {
        $domain->environment()->associate($environment);

        $domain->save();
    }
}

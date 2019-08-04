<?php

namespace App\Http\Controllers\Environment;

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
        $domain = team()->domains()->find($request->domain);

        abort_unless($domain, Response::HTTP_BAD_REQUEST);

        $this->authorize('attach-domain', [$environment, $domain]);

        $domain->environment()->associate($environment);

        return created();
    }
}

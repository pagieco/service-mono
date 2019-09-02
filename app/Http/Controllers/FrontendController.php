<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Enums\ProfileEventType;
use App\Profile;
use App\ProfileEvent;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Router\RouteResolver;
use Illuminate\Support\Facades\Cookie;

class FrontendController
{
    protected $data = [];

    /**
     * Render the routed resource to the frontend.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __invoke(Request $request): View
    {
        $resolver = new RouteResolver($request);

        $domain = $resolver->domain();

        return $resolver
            ->resource($domain)
            ->toResponse($request)
            ->with($this->prepareResponseData($request, $domain));
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return array
     */
    protected function prepareResponseData(Request $request, Domain $domain): array
    {
        $this->identifyProfile($request, $domain);

        return $this->data;
    }

    /**
     * Identify the visitor's profile.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     */
    protected function identifyProfile(Request $request, Domain $domain): void
    {
        if ($profile = Profile::identify($request, $domain)) {
            $this->data['profile_id'] = $profile->id;

            Cookie::queue('profile_id', $profile->id);

            ProfileEvent::recordPageVisit($profile);
        }
    }
}

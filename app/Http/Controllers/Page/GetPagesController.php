<?php

namespace App\Http\Controllers\Page;

use App\Page;
use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\PagesResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetPagesController
{
    use AuthorizesRequests;

    /**
     * Get a list of pages.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\PagesResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Domain $domain)
    {
        $this->authorize('list', Page::class);

        $pages = $domain->pages;

        abort_if($pages->isEmpty(), Response::HTTP_NO_CONTENT);

        return new PagesResource($pages);
    }
}

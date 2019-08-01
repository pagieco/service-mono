<?php

namespace App\Http\Controllers\Page;

use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\PagesResource;

class GetPagesController
{
    /**
     * Get a list of pages.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\PagesResource
     */
    public function __invoke(Request $request, Domain $domain)
    {
        $pages = $domain->pages;

        abort_if($pages->isEmpty(), Response::HTTP_NO_CONTENT);

        return new PagesResource($pages);
    }
}

<?php

namespace App\Http\Controllers\Page;

use App\Page;
use App\Domain;
use App\Http\Resources\PageResource;
use App\Http\Requests\PublishPageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PublishPageController
{
    use AuthorizesRequests;

    public function __invoke(PublishPageRequest $request, Domain $domain, Page $page): PageResource
    {
        $this->authorize('publish', $page);

        $page->publish(
            $request->get('dom'),
            $request->get('css')
        );

        return new PageResource($page);
    }
}

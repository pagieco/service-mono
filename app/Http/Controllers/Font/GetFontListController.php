<?php

namespace App\Http\Controllers\Font;

use App\Font;
use App\Http\Response;
use App\Http\Resources\FontListResource;

class GetFontListController
{
    public function __invoke()
    {
        $fonts = Font::all();

        // The font list can never be empty. If it's empty there is something terribly wrong.
        abort_if($fonts->isEmpty(), Response::HTTP_INTERNAL_SERVER_ERROR);

        return FontListResource::collection($fonts);
    }
}

<?php

namespace App\Http\Controllers\Visitor;

use App\Profile;
use App\Http\Requests\IdentifyVisitorRequest;

class IdentifyVisitorController
{
    public function __invoke(IdentifyVisitorRequest $request)
    {
        if ($request->has('profile_id')) {

        }

        if ($request->has('email')) {

        }
    }
}

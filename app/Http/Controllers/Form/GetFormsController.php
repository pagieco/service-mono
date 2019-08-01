<?php

namespace App\Http\Controllers\Form;

use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\FormsResource;

class GetFormsController
{
    /**
     * Get a list of forms.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\FormsResource
     */
    public function __invoke(Request $request, Domain $domain): FormsResource
    {
        $forms = $domain->forms;

        abort_if($forms->isEmpty(), Response::HTTP_NO_CONTENT);

        return new FormsResource($forms);
    }
}

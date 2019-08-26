<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\Domain;
use App\Http\Response;
use Illuminate\Http\Request;
use App\Http\Resources\FormsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetFormsController
{
    use AuthorizesRequests;

    /**
     * Get a list of forms.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\FormsResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Domain $domain): FormsResource
    {
        $this->authorize('list', Form::class);

        $forms = $domain->forms()->paginate();

        abort_if($forms->isEmpty(), Response::HTTP_NO_CONTENT);

        return new FormsResource($forms);
    }
}

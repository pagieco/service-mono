<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\Domain;
use App\Http\Response;
use App\Http\Resources\FormSubmissionsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GetFormSubmissionsController
{
    use AuthorizesRequests;

    /**
     * Get all form submissions from the given form.
     *
     * @param  \App\Domain $domain
     * @param  \App\Form $form
     * @return \App\Http\Resources\FormSubmissionsResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Domain $domain, Form $form): FormSubmissionsResource
    {
        $this->authorize('view-submissions', $form);

        $submissions = $form->submissions()->paginate();

        abort_if($submissions->isEmpty(), Response::HTTP_NO_CONTENT);

        return new FormSubmissionsResource($submissions);
    }
}

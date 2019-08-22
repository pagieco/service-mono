<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\Domain;
use App\FormField;
use Illuminate\Http\Request;
use App\Http\Resources\FormResource;
use App\Http\Requests\CreateFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateFormController
{
    use AuthorizesRequests;

    /**
     * Handle the create collection request.
     *
     * @param  \App\Http\Requests\CreateFormRequest $request
     * @param  \App\Domain $domain
     * @return \App\Http\Resources\FormResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function __invoke(CreateFormRequest $request, Domain $domain): FormResource
    {
        $this->authorize('create', Form::class);

        $form = $this->createForm($request, $domain);

        foreach ($request->fields as $field) {
            $this->createFormField($domain, $form, $field);
        }

        return new FormResource($form);
    }

    /**
     * Creatae a new collection instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Form
     * @throws \Throwable
     */
    protected function createForm(Request $request, Domain $domain): Form
    {
        $form = new Form($request->all());

        $form->domain()->associate($domain);
        $form->team()->associate(current_team());

        return tap($form)->save();
    }

    /**
     * Create a new form field.
     *
     * @param  \App\Domain $domain
     * @param  \App\Form $form
     * @param  array $field
     * @return \App\FormField
     * @throws \Throwable
     */
    protected function createFormField(Domain $domain, Form $form, array $field): FormField
    {
        $field = new FormField($field);

        $field->domain()->associate($domain);
        $field->team()->associate(current_team());
        $field->form()->associate($form);

        return tap($field)->save();
    }
}

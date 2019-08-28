<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\FormField;
use App\Http\Response;
use App\FormSubmission;
use App\Http\Requests\SubmitFormRequest;
use App\Http\Resources\FormSubmissionResource;

class SubmitFormController
{
    public function __invoke(SubmitFormRequest $request, Form $form)
    {
        // Abort the request when the given signature in the request
        // is invalid or there is no signature at all.
        abort_if(! $request->hasValidSignature(), Response::HTTP_NOT_ACCEPTABLE);

        // Abort the request when there are no form fields
        // present in the current form.
        abort_if($form->fields->isEmpty(), Response::HTTP_BAD_REQUEST);

        $request->validate($this->validatorData($form));

        $submission = $this->insertSubmission($request, $form);

        return new FormSubmissionResource($submission);
    }

    /**
     * Create the validator data.
     *
     * @param  \App\Form $form
     * @return array
     */
    protected function validatorData(Form $form): array
    {
        return (array) $form->fields->mapWithKeys(function (FormField $field): array {
            $key = sprintf('fields.%s', $field->slug);

            return [$key => $field->validations];
        });
    }

    /**
     * Create a new form-submission.
     *
     * @param  \App\Http\Requests\SubmitFormRequest $request
     * @param  \App\Form $form
     * @return \App\FormSubmission
     */
    protected function insertSubmission(SubmitFormRequest $request, Form $form): FormSubmission
    {
        $submission = new FormSubmission([
            'submission_data' => $request->get('fields'),
        ]);

        $submission->form()->associate($form);
        $submission->team()->associate($form->team);
        $submission->domain()->associate($form->domain);

        return tap($submission)->save();
    }
}

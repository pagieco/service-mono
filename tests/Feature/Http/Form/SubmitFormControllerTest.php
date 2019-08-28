<?php

namespace Tests\Feature\Http\Form;

use App\Form;
use App\User;
use App\FormField;
use Tests\TestCase;
use App\Http\Response;
use App\FormSubmission;
use App\Enums\FormFieldValidation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitFormControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_form_could_not_be_found()
    {
        $response = $this->post(route('submit-form', faker()->uuid));

        $response->assertSchema('SubmitForm', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_requires_an_array_of_fields_when_submitting_a_form()
    {
        $form = factory(Form::class)->create();

        factory(FormField::class, 5)->create([
            'form_id' => $form->id,
        ]);

        $response = $this->post(route('submit-form', $form));

        $response->assertSchema('SubmitForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_a_406_bad_request_when_the_route_has_no_valid_signature()
    {
        $form = factory(Form::class)->create();

        factory(FormField::class, 5)->create([
            'form_id' => $form->id,
        ]);

        $response = $this->post(route('submit-form', $form), [
            'fields' => [
                'test-field' => 'test value',
            ]
        ]);

        $response->assertSchema('SubmitForm', Response::HTTP_NOT_ACCEPTABLE);
    }

    /** @test */
    public function it_throws_a_400_bad_request_when_the_form_has_no_fields()
    {
        $form = factory(Form::class)->create();

        $route = URL::temporarySignedRoute('submit-form', now()->addMinute(), $form);

        $response = $this->post($route, [
            'fields' => [
                'test-field' => 'test value',
            ]
        ]);

        $response->assertSchema('SubmitForm', Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function it_throws_a_422_unprocessable_entitry_response_when_the_form_has_validation_errors()
    {
        $form = factory(Form::class)->create();

        factory(FormField::class)->create([
            'form_id' => $form->id,
            'slug' => 'test-field',
            'validations' => [FormFieldValidation::Required],
        ]);

        $route = URL::temporarySignedRoute('submit-form', now()->addMinute(), $form);

        $response = $this->post($route, [
            'fields' => [
                // ...
            ],
        ]);

        $response->assertSchema('SubmitForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_successfully_executes_the_submit_form_route()
    {
        Notification::fake();

        $form = factory(Form::class)->create();
        $user = factory(User::class)->create();

        $form->subscribeToNotifications($user);

        factory(FormField::class)->create([
            'slug' => 'test-field',
            'form_id' => $form->id,
            'validations' => [FormFieldValidation::Required],
        ]);

        $route = URL::temporarySignedRoute('submit-form', now()->addMinute(), $form);

        $response = $this->post($route, [
            'fields' => [
                'test-field' => 'value',
            ],
        ]);

        $this->assertDatabaseHas(FormSubmission::getTableName(), [
            'id' => $response->json('data.id'),
        ]);

        $response->assertSchema('SubmitForm', Response::HTTP_CREATED);

        Notification::assertSentTo($user, FormSubmissionNotification::class);
    }
}

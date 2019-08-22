<?php

namespace Tests\Feature\Http\Form;

use Tests\TestCase;
use App\Http\Response;
use App\Enums\FormFieldType;
use Tests\ValidatesOpenAPISchema;
use App\Enums\FormFieldValidation;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFormControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_requires_the_name_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => 'aa',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_description_length_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'description' => str_repeat('a', 251),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_requires_the_fields_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_an_array_of_fields_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => 'a random string',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_a_required_display_name_key_on_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'display_name' => '',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.display_name'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_length_for_the_display_name_field_in_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'display_name' => 'no',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.display_name'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_length_for_the_display_name_field_in_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'display_name' => str_repeat('a', 101),
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.display_name'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_a_required_slug_key_on_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => '',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_slug_length_in_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'no',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_slug_length_in_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => str_repeat('a', 101),
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_alpha_dash_characters_for_the_slug_key_in_the_fields_array_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'Wrongly formatted slyg',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_a_correct_field_type_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'type' => 'unknown-type',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.type'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_an_array_of_validations_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'validations' => 'unknown-validation',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.validations'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_a_correct_validation_type_when_creating_a_new_form()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'validations' => [
                        'invalid-validation-rule' => true,
                    ],
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.validations'],
        ]);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_a_forbidden_exception_when_the_user_has_no_permission_to_create_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'my-field',
                    'display_name' => 'My Field',
                    'type' => FormFieldType::PlainText,
                    'validations' => [
                        FormFieldValidation::Required => true,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_creates_a_new_collection()
    {
        $this->login()->forceAccess($this->role, 'form:create');

        $response = $this->post(route('create-form', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'my-field',
                    'display_name' => 'My Field',
                    'type' => FormFieldType::PlainText,
                    'validations' => [
                        FormFieldValidation::Required => true,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertSchema($response, 'CreateForm', Response::HTTP_CREATED);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->post(route('create-form', $uuid ?? faker()->uuid));
    }
}

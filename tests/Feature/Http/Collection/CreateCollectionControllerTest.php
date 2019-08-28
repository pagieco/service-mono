<?php

namespace Tests\Feature\Http\Collection;

use Tests\TestCase;
use App\Http\Response;
use Tests\RefreshCollections;
use App\Enums\DatabaseFieldType;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use RefreshCollections;

    /** @test */
    public function it_requires_the_name_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()));

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => 'aa',
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_description_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'description' => str_repeat('a', 251),
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_requires_the_fields_when_create_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_an_array_of_fields_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => 'a random string',
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_a_required_name_key_on_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'name' => '',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_length_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'name' => 'no',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_length_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'name' => str_repeat('a', 101),
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_a_required_slug_key_on_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => '',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_slug_length_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'no',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_slug_length_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => str_repeat('a', 101),
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_alpha_dash_characters_for_the_slug_key_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'slug' => 'Wrongly formatted slug',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_helptext_length_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'helptext' => str_repeat('a', 300),
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.helptext'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_boolean_is_required_field_in_the_fields_array_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'is_required' => 'nope',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.is_required'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_a_correct_field_type_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => [
                'my-field' => [
                    'type' => 'unknown-type',
                ],
            ],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.type'],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_a_forbidden_exception_when_the_user_has_no_permission_to_create_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'description' => faker()->realText(),
            'fields' => [
                'title' => [
                    'name' => 'Title',
                    'slug' => 'title',
                    'helptext' => null,
                    'is_required' => true,
                    'type' => DatabaseFieldType::PlainText,
                ],
            ],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_creates_a_new_collection()
    {
        $this->login()->forceAccess($this->role, 'collection:create');

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'description' => faker()->realText(),
            'fields' => [
                'title' => [
                    'name' => 'Title',
                    'slug' => 'title',
                    'helptext' => null,
                    'is_required' => true,
                    'type' => DatabaseFieldType::PlainText,
                ],
            ],
        ]);

        $response->assertSchema('CreateCollection', Response::HTTP_CREATED);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->post(route('create-collection', $uuid ?? faker()->uuid));
    }
}

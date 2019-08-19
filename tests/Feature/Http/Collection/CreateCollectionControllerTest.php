<?php

namespace Tests\Feature\Http\Collection;

use Tests\TestCase;
use App\Http\Response;
use Tests\RefreshCollections;
use App\Enums\DatabaseFieldType;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use RefreshCollections;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_requires_the_name_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => 'aa',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_description_length_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'description' => str_repeat('a', 251),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_requires_the_fields_when_create_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_an_array_of_fields_when_creating_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'fields' => 'a random string',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.name'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.slug'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.helptext'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.is_required'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => ['fields.my-field.type'],
        ]);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_FORBIDDEN);
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

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertSchema($response, 'CreateCollection', Response::HTTP_CREATED);
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

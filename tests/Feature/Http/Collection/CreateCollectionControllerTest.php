<?php

namespace Tests\Feature\Http\Collection;

use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
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
    public function it_throws_a_forbidden_exception_when_the_user_has_no_permission_to_create_a_new_collection()
    {
        $this->login();

        $response = $this->post(route('create-collection', $this->domain()), [
            'name' => faker()->name,
            'description' => faker()->realText(),
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

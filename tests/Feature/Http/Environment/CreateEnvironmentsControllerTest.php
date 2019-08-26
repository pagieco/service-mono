<?php

namespace Tests\Feature\Http\Environment;

use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEnvironmentsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_requires_the_name_when_creating_a_new_environment()
    {
        $this->login();

        $response = $this->post(route('create-environment'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateEnvironment', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_creating_a_new_environment()
    {
        $this->login();

        $response = $this->post(route('create-environment'), [
            'name' => 'a',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateEnvironment', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_creating_a_new_environment()
    {
        $this->login();

        $response = $this->post(route('create-environment'), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateEnvironment', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_a_forbidden_exception_when_the_user_has_no_permission_to_create_a_new_environment()
    {
        $this->login();

        $response = $this->post(route('create-environment'), [
            'name' => 'test environment',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'CreateEnvironment', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_creates_a_new_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:create');

        $response = $this->post(route('create-environment'), [
            'name' => 'test environment',
        ]);

        $this->assertSchema($response, 'CreateEnvironment', Response::HTTP_CREATED);

        $this->assertDatabaseHas(Environment::getTableName(), [
            'id' => $response->json('data.id'),
        ]);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('create-environment'));
    }
}

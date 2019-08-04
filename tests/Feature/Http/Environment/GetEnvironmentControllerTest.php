<?php

namespace Tests\Feature\Http\Environment;

use App\User;
use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEnvironmentControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_environment_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-environment', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_environment()
    {
        $this->login();

        $response = $this->get(route('get-environment', $this->createTestResource()->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_environment_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $response = $this->get(route('get-environment', $environment->id));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_sucessfully_executes_the_get_environment_route()
    {
        $this->login()->forceAccess($this->role, 'environment:view');

        $response = $this->get(route('get-environment', $this->createTestResource()->id));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetEnvironment', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-environment', $uuid ?? faker()->uuid));
    }

    /**
     * Get the route UUID used for verified route requests.
     *
     * @return string
     */
    protected function getRouteUuid(): string
    {
        return $this->createTestResource()->id;
    }

    /**
     * Create an environment resource user for testing.
     *
     * @return \App\Environment
     */
    protected function createTestResource(): Environment
    {
        return factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);
    }
}

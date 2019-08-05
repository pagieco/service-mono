<?php

namespace Tests\Feature\Http\Environment;

use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEnvironmentControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_environment_could_not_be_found()
    {
        $this->login();

        $response = $this->patch(route('update-environment', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_environment()
    {
        $this->login();

        $response = $this->patch(route('update-environment', $this->createTestResource()->id), [
            'name' => 'Environment name',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_exception_when_the_environment_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $response = $this->patch(route('update-environment', $environment->id), [
            'name' => 'Environment name',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_validates_on_name_when_updating_an_environment()
    {
        $this->login();

        $response = $this->patch(route('update-environment', $this->createTestResource()->id), [
            'name' => null,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_updating_an_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:update');

        $response = $this->patch(route('update-environment', $this->createTestResource()->id), [
            'name' => 'a',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_updating_an_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:update');

        $response = $this->patch(route('update-environment', $this->createTestResource()->id), [
            'name' => str_repeat('a', 105),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_successfully_updates_a_given_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:update');

        $environment = $this->createTestResource();

        $newName = 'New environment name';

        $response = $this->patch(route('update-environment', $environment->id), [
            'name' => $newName,
        ]);

        $this->assertDatabaseHas(Environment::getTableName(), [
            'name' => $newName,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'UpdateEnvironment', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->patch(route('update-environment', $uuid ?? faker()->uuid));
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

<?php

namespace Tests\Feature\Http\Environment;

use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEnvironmentControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_404_exception_when_the_environment_could_not_be_found()
    {
        $this->login();

        $response = $this->delete(route('delete-environment', faker()->uuid));

        $response->assertSchema('DeleteEnvironment', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_to_user_has_no_access_to_delete_the_environment()
    {
        $this->login();

        $response = $this->delete(route('delete-environment', $this->createTestResource()->id));

        $response->assertSchema('DeleteEnvironment', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_deletes_the_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:delete');

        $environment = $this->createTestResource();

        $this->assertNotNull($this->domain()->environment);

        $response = $this->delete(route('delete-environment', $environment->id));

        $response->assertSchema('DeleteEnvironment', Response::HTTP_NO_CONTENT);

        $this->domain()->refresh();

        $this->assertNull($this->domain()->environment);

        $this->assertDatabaseMissing(Environment::getTableName(), [
            'id' => $environment->id,
        ]);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->delete(route('delete-environment', $uuid ?? faker()->uuid));
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
        $domain = $this->domain();

        return $domain->environment;
    }
}

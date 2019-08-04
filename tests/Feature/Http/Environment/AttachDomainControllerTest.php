<?php

namespace Tests\Feature\Http\Environment;

use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachDomainControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_environment_could_not_be_found()
    {
        $this->login();

        $response = $this->put(route('attach-domain-to-environment', faker()->uuid), [
            'domain' => $this->domain()->id,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_400_bad_request_exception_when_the_domain_could_not_be_found()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $response = $this->put(route('attach-domain-to-environment', $this->createTestResource()->id), [
            'domain' => faker()->uuid,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_environment()
    {
        $this->login();

        $response = $this->put(route('attach-domain-to-environment', $this->createTestResource()->id), [
            'domain' => $this->domain()->id,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_attach_domain_to_environment_route()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $response = $this->put(route('attach-domain-to-environment', $this->createTestResource()->id), [
            'domain' => $this->domain()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->put(route('attach-domain-to-environment', $uuid ?? faker()->uuid));
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

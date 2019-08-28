<?php

namespace Tests\Feature\Http\Teams;

use App\Domain;
use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetDomainControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_404_exception_when_the_domain_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-domain', faker()->uuid));

        $response->assertSchema('GetDomain', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_domain()
    {
        $this->login();

        $response = $this->get(route('get-domain', $this->domain()->id));

        $response->assertSchema('GetDomain', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_domain_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $environment = factory(Environment::class)->create();
        $domain = factory(Domain::class)->make();
        $domain->environment()->associate($environment);
        $domain->save();

        $response = $this->get(route('get-domain', $domain->id));

        $response->assertSchema('GetDomain', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_successfully_executes_the_get_domains_route()
    {
        $this->login()->forceAccess($this->role, 'domain:view');

        $response = $this->get(route('get-domain', $this->domain()->id));

        $response->assertSchema('GetDomain', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-domain', $uuid ?? faker()->uuid));
    }
}

<?php

namespace Tests\Feature\Http\Domain;

use App\Domain;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetDomainsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_returns_an_empty_response_when_no_domains_where_found()
    {
        $this->login()->forceAccess($this->role, 'domain:list');

        $response = $this->get(route('get-domains'));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_domains_from_other_teams()
    {
        $this->login()->forceAccess($this->role, 'domain:list');
        $this->domain();

        factory(Domain::class)->create();

        $response = $this->get(route('get-domains'));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_list_of_domains()
    {
        $this->login();

        $response = $this->get(route('get-domains'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_domain_route()
    {
        $this->login()->forceAccess($this->role, 'domain:list');
        $this->domain();

        $response = $this->get(route('get-domains'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetDomains', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('get-domains'));
    }
}

<?php

namespace Tests\Feature\Http\Collection;

use App\Collection;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCollectionsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_returns_an_empty_response_when_no_collections_where_found()
    {
        $this->login()->forceAccess($this->role, 'collection:list');

        $response = $this->get(route('get-collections', $this->domain()->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_collections_from_other_teams()
    {
        $this->login()->forceAccess($this->role, 'collection:list');

        $domain = $this->domain();

        factory(Collection::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Collection::class)->create();

        $response = $this->get(route('get-collections', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_doesnt_include_collections_from_other_domains()
    {
        $this->login()->forceAccess($this->role, 'collection:list');

        $domain = $this->domain();

        factory(Collection::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Collection::class)->create([
            'team_id' => $this->team->id,
        ]);

        $response = $this->get(route('get-collections', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_list_of_collections()
    {
        $this->login();

        $response = $this->get(route('get-collections', $this->domain()->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_collections_route()
    {
        $this->login()->forceAccess($this->role, 'collection:list');

        $domain = $this->domain();

        factory(Collection::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain->id,
        ]);

        $response = $this->get(route('get-collections', $domain->id));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetCollections', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-collections', $uuid ?? faker()->uuid));
    }
}

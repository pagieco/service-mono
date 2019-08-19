<?php

namespace Tests\Feature\Http\Collection;

use Tests\TestCase;
use App\Collection;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_collection_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-collection', [$this->domain(), faker()->uuid]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetCollection', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_collection()
    {
        $this->login();

        $collection = $this->createTestResource();

        $response = $this->get(route('get-collection', [$this->domain(), $collection->_id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'GetCollection', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_collection_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $collection = factory(Collection::class)->create();

        $response = $this->get(route('get-collection', [$this->domain(), $collection->_id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetCollection', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_successfully_executes_the_get_collection_route()
    {
        $this->login()->forceAccess($this->role, 'collection:view');

        $collection = $this->createTestResource();

        $response = $this->get(route('get-collection', [$this->domain(), $collection->_id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetCollection', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|array|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest($uuid = null): TestResponse
    {
        if (is_array($uuid)) {
            list($domain, $collection) = $uuid;
        } else {
            $domain = faker()->uuid;
            $collection = faker()->uuid;
        }

        return $this->get(route('get-collection', [$domain, $collection]));
    }

    /**
     * Get the route UUID used for verified route requests.
     *
     * @return string|array
     */
    protected function getRouteUuid()
    {
        $testResource = $this->createTestResource();

        return [$testResource->domain_id, $testResource->_id];
    }

    /**
     * Create an environment resource user for testing.
     *
     * @return \App\Collection
     */
    protected function createTestResource(): Collection
    {
        return factory(Collection::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);
    }
}

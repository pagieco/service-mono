<?php

namespace Tests\Feature\Http\Collection;

use App\Collection;
use Tests\TestCase;
use App\Http\Response;
use Tests\RefreshCollections;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use RefreshCollections;

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_collection_could_not_be_found()
    {
        $this->login();

        $response = $this->delete(route('delete-collection', [$this->domain(), faker()->uuid]));

        $response->assertSchema('DeleteCollection', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_delete_the_collection()
    {
        $this->login();

        $resource = $this->createTestResource();

        $response = $this->delete(route('delete-collection', [
            $resource->domain_id,
            $resource->id,
        ]));

        $response->assertSchema('DeleteCollection', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_collection_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $collection = factory(Collection::class)->create();

        $response = $this->delete(route('delete-collection', [$this->domain(), $collection->id]));

        $response->assertSchema('DeleteCollection', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_successfully_deletes_the_collection()
    {
        $this->login()->forceAccess($this->role, 'collection:delete');

        $resource = $this->createTestResource();

        $response = $this->delete(route('delete-collection', [
            $resource->domain_id,
            $resource->id,
        ]));

        $response->assertSchema('DeleteCollection', Response::HTTP_NO_CONTENT);
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

        return $this->delete(route('delete-collection', [$domain, $collection]));
    }

    /**
     * Get the route UUID used for verified route requests.
     *
     * @return string|array
     */
    protected function getRouteUuid()
    {
        $testResource = $this->createTestResource();

        return [$testResource->domain_id, $testResource->id];
    }

    /**
     * Create an environment resource user for testing.
     *
     * @return \App\Page
     */
    protected function createTestResource(): Collection
    {
        return factory(Collection::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);
    }
}

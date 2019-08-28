<?php

namespace Tests\Feature\Http\Asset;

use App\Asset;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetAssetControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_asset_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-asset', [$this->domain(), faker()->uuid]));

        $response->assertSchema('GetAsset', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_asset()
    {
        $this->login();

        $asset = $this->createTestResource();

        $response = $this->get(route('get-asset', [$this->domain(), $asset->id]));

        $response->assertSchema('GetAsset', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_asset_assets_but_is_not_part_of_the_team()
    {
        $this->login();

        $asset = factory(Asset::class)->create();

        $response = $this->get(route('get-asset', [$this->domain(), $asset->id]));

        $response->assertSchema('GetAsset', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_successfully_executes_the_get_asset_route()
    {
        $this->login()->forceAccess($this->role, 'asset:view');

        $asset = $this->createTestResource();

        $response = $this->get(route('get-asset', [$this->domain(), $asset->id]));

        $response->assertSchema('GetAsset', Response::HTTP_OK);
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
            list($domain, $asset) = $uuid;
        } else {
            $domain = faker()->uuid;
            $asset = faker()->uuid;
        }

        return $this->get(route('get-asset', [$domain, $asset]));
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
    protected function createTestResource(): Asset
    {
        return factory(Asset::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);
    }
}

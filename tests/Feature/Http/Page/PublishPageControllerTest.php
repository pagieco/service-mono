<?php

namespace Tests\Feature\Http\Page;

use App\Page;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublishPageControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_validates_for_dom_presence_when_publishing_a_page()
    {
        $this->login();

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);

        $response = $this->put(route('publish-page', [
            $page->domain_id,
            $page->id,
        ]), [
            'css' => [],
        ]);

        $response->assertSchema('PublishPage', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_an_array_of_dom_nodes_when_publishing_a_page()
    {
        $this->login();

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);

        $response = $this->put(route('publish-page', [
            $page->domain_id,
            $page->id,
        ]), [
            'dom' => '',
            'css' => [],
        ]);

        $response->assertSchema('PublishPage', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_css_presents_when_publishing_a_page()
    {
        $this->login();

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);

        $response = $this->put(route('publish-page', [
            $page->domain_id,
            $page->id,
        ]), [
            'dom' => [],
        ]);

        $response->assertSchema('PublishPage', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_for_an_array_of_css_rules_when_publishing_a_page()
    {
        $this->login();

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);

        $response = $this->put(route('publish-page', [
            $page->domain_id,
            $page->id,
        ]), [
            'dom' => [],
            'css' => '',
        ]);

        $response->assertSchema('PublishPage', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_correctly_publishes_the_page()
    {
        Storage::fake();

        $this->login()->forceAccess($this->role, 'page:publish');

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);

        $response = $this->put(route('publish-page', [
            $page->domain_id,
            $page->id,
        ]), [
            'dom' => [
                'test'
            ],
            'css' => [
                '@media screen' => [

                ],
            ],
        ]);

        $this->assertNotNull($page->domain->css_file);

        $response->assertSchema('PublishPage', Response::HTTP_OK);
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
            list($domain, $page) = $uuid;
        } else {
            $domain = faker()->uuid;
            $page = faker()->uuid;
        }

        return $this->put(route('publish-page', [$domain, $page]));
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
    protected function createTestResource(): Page
    {
        return factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);
    }
}

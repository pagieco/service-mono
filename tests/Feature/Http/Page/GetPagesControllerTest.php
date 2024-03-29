<?php

namespace Tests\Feature\Http\Page;

use App\Page;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPagesControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_returns_an_empty_response_when_no_pages_where_found()
    {
        $this->login()->forceAccess($this->role, 'page:list');

        $response = $this->get(route('get-pages', $this->domain()->id));

        $response->assertSchema('GetPages', Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_pages_from_other_teams()
    {
        $this->login()->forceAccess($this->role, 'page:list');

        $domain = $this->domain();

        factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Page::class)->create();

        $response = $this->get(route('get-pages', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_doesnt_include_pages_from_other_domains()
    {
        $this->login()->forceAccess($this->role, 'page:list');

        $domain = $this->domain();

        factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Page::class)->create([
            'team_id' => $this->team->id,
        ]);

        $response = $this->get(route('get-pages', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_list_of_pages()
    {
        $this->login();

        $response = $this->get(route('get-pages', $this->domain()->id));

        $response->assertSchema('GetPages', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_pages_route()
    {
        $this->login()->forceAccess($this->role, 'page:list');

        $domain = $this->domain();

        factory(Page::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        $response = $this->get(route('get-pages', $domain->id));

        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));

        $response->assertSchema('GetPages', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-pages', $uuid ?? faker()->uuid));
    }
}

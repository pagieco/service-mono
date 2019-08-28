<?php

namespace Tests\Feature\Http\Workflow;

use App\Workflow;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetWorkflowsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_returns_an_empty_response_when_no_workflows_where_found()
    {
        $this->login()->forceAccess($this->role, 'workflow:list');

        $response = $this->get(route('get-workflows'));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_workflows_from_other_teams()
    {
        $this->login()->forceAccess($this->role, 'workflow:list');

        $this->team->workflows()->create(
            factory(Workflow::class)->raw()
        );

        factory(Workflow::class)->create();

        $response = $this->get(route('get-workflows'));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_list_of_workflows()
    {
        $this->login();

        $response = $this->get(route('get-workflows'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_workflows_route()
    {
        $this->login()->forceAccess($this->role, 'workflow:list');

        factory(Workflow::class)->create([
            'team_id' => $this->team->id,
        ]);

        $response = $this->get(route('get-workflows'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));

        $response->assertSchema('GetWorkflows', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('get-workflows'));
    }
}

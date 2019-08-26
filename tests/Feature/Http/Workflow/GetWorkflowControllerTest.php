<?php

namespace Tests\Feature\Http\Workflow;

use App\Workflow;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetWorkflowControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_not_found_excepton_when_the_workflow_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-workflow', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetWorkflow', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_workflow()
    {
        $this->login();

        $response = $this->get(route('get-workflow', $this->createTestResource()->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'GetWorkflow', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_workflow_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $workflow = factory(Workflow::class)->create();

        $response = $this->get(route('get-workflow', $workflow->id));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetWorkflow', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_successfully_executes_the_get_workflow_route()
    {
        $this->login()->forceAccess($this->role, 'workflow:view');

        $response = $this->get(route('get-workflow', $this->createTestResource()->id));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetWorkflow', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-workflow', $uuid ?? faker()->uuid));
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
     * @return \App\Workflow
     */
    protected function createTestResource(): Workflow
    {
        return factory(Workflow::class)->create([
            'team_id' => $this->team->id,
        ]);
    }
}

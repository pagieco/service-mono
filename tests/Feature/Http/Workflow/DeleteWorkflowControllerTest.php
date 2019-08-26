<?php

namespace Tests\Feature\Http\Workflow;

use App\Workflow;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteWorkflowControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_workflow_could_not_be_found()
    {
        $this->login();

        $response = $this->delete(route('delete-workflow', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_delete_the_workflow()
    {
        $this->login();

        $response = $this->delete(route('delete-workflow', $this->createTestResource()->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_deletes_the_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:delete');

        $workflow = $this->createTestResource();

        $response = $this->delete(route('delete-workflow', $workflow->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(Workflow::getTableName(), [
            'id' => $workflow->id,
        ]);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->delete(route('delete-workflow', $uuid ?? faker()->uuid));
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
     * Create an workflow resource user for testing.
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

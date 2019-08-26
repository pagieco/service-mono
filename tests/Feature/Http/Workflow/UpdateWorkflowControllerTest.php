<?php

namespace Tests\Feature\Http\Workflow;

use App\Workflow;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateWorkflowControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_workflow_could_not_be_found()
    {
        $this->login();

        $response = $this->patch(route('update-workflow', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_workflow()
    {
        $this->login();

        $response = $this->patch(route('update-workflow', $this->createTestResource()->id), [
            'name' => 'Workflow name',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_exception_when_the_workflow_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $workflow = factory(Workflow::class)->create();

        $response = $this->patch(route('update-workflow', $workflow->id), [
            'name' => 'Workflow name',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_validates_on_name_when_updating_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $response = $this->patch(route('update-workflow', $this->createTestResource()->id), [
            'name' => null,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_updating_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $response = $this->patch(route('update-workflow', $this->createTestResource()->id), [
            'name' => 'a',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_updatting_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $response = $this->patch(route('update-workflow', $this->createTestResource()->id), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_description_length_when_updating_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $response = $this->patch(route('update-workflow', $this->createTestResource()->id), [
            'name' => 'Workflow name',
            'description' => str_repeat('a', 251),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_successfully_updates_a_given_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $workflow = $this->createTestResource();

        $newName = 'New workflow name';

        $response = $this->patch(route('update-workflow', $workflow->id), [
            'name' => $newName,
        ]);

        $this->assertDatabaseHas(Workflow::getTableName(), [
            'name' => $newName,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'UpdateWorkflow', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->patch(route('update-workflow', $uuid ?? faker()->uuid));
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

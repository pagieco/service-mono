<?php

namespace Tests\Feature\Http\Workflow;

use App\Workflow;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateWorkflowControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_requires_the_name_when_creating_a_new_workflow()
    {
        $this->login();

        $response = $this->post(route('create-workflow', $this->domain()));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_creating_a_new_workflow()
    {
        $this->login();

        $response = $this->post(route('create-workflow', $this->domain()), [
            'name' => 'aa',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_creating_a_new_workflow()
    {
        $this->login();

        $response = $this->post(route('create-workflow', $this->domain()), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_description_length_when_creating_a_new_workflow()
    {
        $this->login();

        $response = $this->post(route('create-workflow', $this->domain()), [
            'description' => str_repeat('a', 251),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_create_a_new_workflow()
    {
        $this->login();

        $response = $this->post(route('create-workflow', $this->domain()), [
            'name' => faker()->name,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_creates_a_new_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:create');

        $response = $this->post(route('create-workflow', $this->domain()), [
            'name' => faker()->name,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertSchema($response, 'CreateWorkflow', Response::HTTP_CREATED);

        $this->assertDatabaseHas(Workflow::getTableName(), [
            'id' => $response->json('data.id'),
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
        return $this->post(route('create-workflow', $uuid ?? faker()->uuid));
    }
}

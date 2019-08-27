<?php

namespace Tets\Unit\Policies;

use App\Workflow;
use Tests\TestCase;
use App\Policies\WorkflowPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_workflows()
    {
        $this->login();

        $this->assertFalse((new WorkflowPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_workflows()
    {
        $this->login()->forceAccess($this->role, 'workflow:list');

        $this->assertTrue((new WorkflowPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_a_workflow()
    {
        $this->login();

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->view($this->user, $workflow));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_a_workflow_but_the_workflow_doenst_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'workflow:view');

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->view($this->user, $workflow));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:view');

        $workflow = factory(Workflow::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new WorkflowPolicy)->view($this->user, $workflow));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_create_a_new_workflow()
    {
        $this->login();

        $this->assertFalse((new WorkflowPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_create_a_new_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:create');

        $this->assertTrue((new WorkflowPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_update_a_workflow()
    {
        $this->login();

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->update($this->user, $workflow));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_update_a_workflow_but_the_workflow_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->update($this->user, $workflow));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_update_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:update');

        $workflow = factory(Workflow::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new WorkflowPolicy)->update($this->user, $workflow));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_delete_a_workflow()
    {
        $this->login();

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->delete($this->user, $workflow));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_delete_a_workflow_but_the_workflow_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'workflow:delete');

        $workflow = factory(Workflow::class)->create();

        $this->assertFalse((new WorkflowPolicy)->delete($this->user, $workflow));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_delete_a_workflow()
    {
        $this->login()->forceAccess($this->role, 'workflow:delete');

        $workflow = factory(Workflow::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new WorkflowPolicy)->delete($this->user, $workflow));
    }
}

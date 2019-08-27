<?php

namespace Tets\Unit\Policies;

use App\Team;
use Tests\TestCase;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_teams()
    {
        $this->login();

        $this->assertFalse((new TeamPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_teams()
    {
        $this->login()->forceAccess($this->role, 'team:list');

        $this->assertTrue((new TeamPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_a_team()
    {
        $this->login();

        $team = factory(Team::class)->create();

        $this->assertFalse((new TeamPolicy)->view($this->user, $team));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_a_team_but_the_user_has_no_access_to_the_team()
    {
        $this->login()->forceAccess($this->role, 'team:view');

        $team = factory(Team::class)->create();

        $this->assertFalse((new TeamPolicy)->view($this->user, $team));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_a_team()
    {
        $team = factory(Team::class)->create();

        $this->login()->forceAccess($this->role, 'team:view');

        $this->user->joinTeam($team);

        $this->assertTrue((new TeamPolicy)->view($this->user, $team));
    }
}

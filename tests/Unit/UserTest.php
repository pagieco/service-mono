<?php

namespace Tests\Unit;

use App\Role;
use App\Team;
use App\User;
use App\Permission;
use App\Events\TeamJoined;
use App\Events\TeamSwitched;
use App\Concerns\Paginatable;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = User::class;

    /** @test */
    public function it_correctly_implements_the_paginatable_concern()
    {
        $this->assertTrue(in_array(Paginatable::class, class_uses($this->model)));
    }

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_can_get_the_current_team()
    {
        $this->fakeModelEvents();

        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $user->teams()->attach($team);

        $this->assertNotNull($user->currentTeam());
        $this->assertInstanceOf(Team::class, $user->currentTeam());
    }

    /** @test */
    public function it_can_join_a_team()
    {
        $this->fakeModelEvents();

        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $user->joinTeam($team);

        $this->assertCount(1, $user->teams);

        Event::assertDispatched(TeamJoined::class, function ($e) use ($user, $team) {
            return $e->user->id === $user->id
                && $e->team->id === $team->id;
        });
    }

    /** @test */
    public function it_cant_switch_to_a_team_that_the_user_hasnt_joined()
    {
        $this->expectException(AuthorizationException::class);

        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();

        $user = factory(User::class)->create();

        $user->joinTeam($team1);

        $user->switchToTeam($team2);
        $user->currentTeam();
    }

    /** @test */
    public function it_can_switch_to_another_team()
    {
        $this->fakeModelEvents();

        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();

        $user = factory(User::class)->create();

        $user->joinTeam($team1);
        $user->joinTeam($team2);

        $user->switchToTeam($team2);

        Event::assertDispatched(TeamSwitched::class, function ($e) use ($user, $team2) {
            return $e->user->id === $user->id
                && $e->team->id === $team2->id;
        });
    }

    /** @test */
    public function it_can_get_the_users_teammates()
    {
        $team = factory(Team::class)->create();

        $users = factory(User::class, 5)
            ->create()
            ->each(function ($user) use ($team) {
                $user->joinTeam($team);
            });

        $this->assertCount(4, $users->first()->teamMates());
    }

    /** @test */
    public function it_cannot_assign_a_role_that_does_not_exist()
    {
        $user = factory(User::class)->create();

        $user->assignRole('non-existing-role');

        $this->assertTrue($user->roles->isEmpty());
    }

    /** @test */
    public function it_cannot_assign_a_role_that_doesnt_belong_to_the_current_workspace()
    {
        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();

        $user->assignRole($role->slug);

        $this->assertTrue($user->roles->isEmpty());
    }

    /** @test */
    public function it_can_assign_a_role()
    {
        $this->login();

        $role = factory(Role::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->user->assignRole($role->id);

        $this->assertFalse($this->user->roles->isEmpty());
    }

    /** @test */
    public function it_can_check_if_the_user_has_access_to_the_permission()
    {
        $this->login();

        $permission = factory(Permission::class)->create();

        $role = factory(Role::class)->create([
            'team_id' => $this->team->id,
        ]);

        $role->assignPermission($permission->slug);

        $this->user->assignRole($role->id);

        $this->assertTrue($this->user->hasAccess($permission->slug));
    }
}

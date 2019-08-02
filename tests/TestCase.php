<?php

namespace Tests;

use App\Role;
use App\Team;
use App\User;
use App\Domain;
use App\Environment;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The currently logged in user.
     *
     * @var null|\App|User
     */
    protected $user = null;

    /**
     * The team the user is currently logged in to.
     *
     * @var null|\App\Team
     */
    protected $team = null;

    /**
     * The domain the use is currently attached to.
     *
     * @var null|\App\Domain
     */
    protected $domain = null;

    /**
     * The user's admin role.
     *
     * @var null|\App\Role
     */
    protected $role = null;

    protected function fakeModelEvents()
    {
        $initialDispatcher = Event::getFacadeRoot();

        Event::fake();
        Model::setEventDispatcher($initialDispatcher);
    }

    protected function login(User $user = null): User
    {
        if (is_null($user)) {
            $user = factory(User::class)->create();
        }

        $team = factory(Team::class)->create();

        $user->teams()->attach($team);

        $this->user = $user;
        $this->team = $team;

        $this->defaultHeaders['x-team-id'] = $team->getKey();

        $this->actingAs($user);
        Passport::actingAs($user);

        $this->defaultHeaders['Authorization'] = "Bearer {$user->api_token}";

        $this->assignAdminRoleToUser($user, $team);

        return $user;
    }

    protected function domain()
    {
        if (! $this->domain) {
            $environment = $this->team->environments()->create(
                factory(Environment::class)->raw()
            );

            $domain = new Domain(factory(Domain::class)->raw());
            $domain->team()->associate($this->team);
            $domain->environment()->associate($environment);
            $domain->save();

            $this->domain = $domain;
        }

        return $this->domain;
    }

    protected function assignAdminRoleToUser()
    {
        $role = new Role([
            'name' => 'Admin',
        ]);

        $role->team()->associate($this->team);

        $role->save();

        $this->user->assignRole($role->id);

        $this->role = $role;
    }
}

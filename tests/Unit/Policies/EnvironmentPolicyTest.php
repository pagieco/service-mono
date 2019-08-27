<?php

namespace Tests\Unit\Policies;

use App\Domain;
use App\Environment;
use App\Policies\EnvironmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnvironmentPolicyTest extends PolicyTestCase
{
    use RefreshDatabase;

    protected $policyList = [
        'environment:list',
        'environment:create',
        'environment:view',
        'environment:attach-domain',
        'environment:detach-domain',
        'environment:update',
        'environment:delete',
    ];

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_environments()
    {
        $this->login();

        $this->assertFalse((new EnvironmentPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_environments()
    {
        $this->login()->forceAccess($this->role, 'environment:list');

        $this->assertTrue((new EnvironmentPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_create_a_new_environment()
    {
        $this->login();

        $this->assertFalse((new EnvironmentPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_create_a_new_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:create');

        $this->assertTrue((new EnvironmentPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_an_environment()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->view($this->user, $environment));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_an_environment_but_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:view');

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->view($this->user, $environment));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_an_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:view');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new EnvironmentPolicy)->view($this->user, $environment));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_attach_a_domain()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->attachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_attach_a_domain_but_the_environment_and_domain_dont_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->attachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_attach_a_domain_but_the_environment_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertFalse((new EnvironmentPolicy)->view($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_attach_a_domain_but_the_domain_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->attachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_attach_a_domain()
    {
        $this->login()->forceAccess($this->role, 'environment:attach-domain');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $domain = factory(Domain::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new EnvironmentPolicy)->attachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_detach_a_domain()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->detachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_detach_a_domain_but_the_environment_and_domain_dont_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:detach-domain');

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->detachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_detach_a_domain_but_the_environment_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:detach-domain');

        $environment = factory(Environment::class)->create();

        $domain = factory(Domain::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertFalse((new EnvironmentPolicy)->detachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_detach_a_domain_but_the_domain_doesnt_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'environment:detach-domain');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->detachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_detach_a_domain()
    {
        $this->login()->forceAccess($this->role, 'environment:detach-domain');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $domain = factory(Domain::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new EnvironmentPolicy)->detachDomain($this->user, $environment, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_update_an_environment()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->update($this->user, $environment));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_update_an_environment_but_the_environment_doesnt_belong_to_the_team()
    {
        $this->login()->forceAccess($this->role, 'environment:update');

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->update($this->user, $environment));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_update_an_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:update');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new EnvironmentPolicy)->update($this->user, $environment));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_delete_an_environment()
    {
        $this->login();

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->delete($this->user, $environment));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_delete_an_environment_but_the_environment_doesnt_belong_to_the_team()
    {
        $this->login()->forceAccess($this->role, 'environment:delete');

        $environment = factory(Environment::class)->create();

        $this->assertFalse((new EnvironmentPolicy)->delete($this->user, $environment));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_delete_an_environment()
    {
        $this->login()->forceAccess($this->role, 'environment:delete');

        $environment = factory(Environment::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new EnvironmentPolicy)->delete($this->user, $environment));
    }
}

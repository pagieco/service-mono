<?php

namespace Tets\Unit\Policies;

use App\Domain;
use Tests\TestCase;
use App\Policies\DomainPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_domains()
    {
        $this->login();

        $this->assertFalse((new DomainPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_domains()
    {
        $this->login()->forceAccess($this->role, 'domain:list');

        $this->assertTrue((new DomainPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_the_domain()
    {
        $this->login();

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new DomainPolicy)->view($this->user, $domain));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_a_domain_but_the_domain_belongs_to_another_team()
    {
        $this->login()->forceAccess($this->role, 'domain:view');

        $domain = factory(Domain::class)->create();

        $this->assertFalse((new DomainPolicy)->view($this->user, $domain));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_a_domain()
    {
        $this->login()->forceAccess($this->role, 'domain:view');

        $domain = factory(Domain::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new DomainPolicy)->view($this->user, $domain));
    }
}

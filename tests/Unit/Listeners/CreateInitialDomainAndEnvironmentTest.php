<?php

namespace Tests\Unit\Listeners;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateInitialDomainAndEnvironmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_initial_domain_and_enviroment_when_the_registered_event_is_triggered()
    {
        $user = factory(User::class)->create();

        event(new Registered($user));

        $this->assertNotNull($user->currentTeam()->domains);
        $this->assertNotNull($user->currentTeam()->environments);
    }

    /** @test */
    public function it_creates_an_initial_workflow_when_the_registered_event_is_triggered()
    {
        $user = factory(User::class)->create();

        event(new Registered($user));

        $this->assertNotNull($user->currentTeam()->workflows);
    }
}

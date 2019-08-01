<?php

namespace Tests\Unit\Listeners;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePersonalUserTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_personal_team_for_the_user_when_the_registered_event_is_triggered()
    {
        $user = factory(User::class)->create();

        event(new Registered($user));

        $this->assertEquals($user->teams->first()->name, 'Personal');
    }
}

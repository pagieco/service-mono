<?php

namespace Tests\Unit\Listeners;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogUserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_logs_the_users_ip_and_last_login_date_when_logging_in()
    {
        $this->createPassportClient();

        $client = DB::table('oauth_clients')->get()->last();

        $user = factory(User::class)->create();

        event(new AccessTokenCreated(faker()->uuid, $user->id, $client->id));

        $user->refresh();

        $this->assertNotNull($user->last_ip);
        $this->assertNotNull($user->last_login_at);
    }

    protected function createPassportClient()
    {
        $this->artisan('passport:install');

        $client = DB::table('oauth_clients')->get()->last();

        config()->set('services.passport.client.id', $client->id);
        config()->set('services.passport.client.secret', $client->secret);
    }
}

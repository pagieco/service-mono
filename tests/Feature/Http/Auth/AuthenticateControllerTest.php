<?php

namespace Tests\Feature\Http\Auth;

use App\User;
use Tests\TestCase;
use App\Http\Response;
use GuzzleHttp\Client;
use Tests\ValidatesOpenApiSchema;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticateControllerTest extends TestCase
{
    use RefreshDatabase;
    use ValidatesOpenApiSchema;

    /** @test */
    public function it_validates_on_presence_of_email()
    {
        $response = $this->post(route('authenticate'), [
            'password' => 'my-password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_correct_email()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'non-email-string',
            'password' => 'my-password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_max_length_for_email()
    {
        $response = $this->post(route('authenticate'), [
            'email' => str_repeat('a', 110),
            'password' => 'my-password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_presence_of_password()
    {
        $response = $this->post(route('authenticate'), [
            'email' => faker()->email,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function it_throws_an_unauthorized_exception_for_an_invalid_email()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_throws_an_unauthorized_exception_for_an_invalid_password()
    {
        $this->mock(Client::class, function ($mock) {
            $mock->shouldReceive('post')->once()->andReturn(new Psr7Response(401));
        });

        $user = factory(User::class)->create();

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertSchema($response, 'Authenticate', Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_can_authenticate_a_user()
    {
        $this->mock(Client::class, function ($mock) {
            // todo: refactor this mock
            $response = new Psr7Response(200, [], json_encode([
                'access_token' => 'access_token',
            ]));

            $mock->shouldReceive('post')
                ->once()
                ->andReturn($response);
        });

        $this->createPassportClient();

        $user = factory(User::class)->create();

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotNull($response->json('access_token'));
    }

    protected function createPassportClient()
    {
        $this->artisan('passport:install');

        $client = DB::table('oauth_clients')->get()->last();

        config()->set('services.passport.client.id', $client->id);
        config()->set('services.passport.client.secret', $client->secret);
    }
}

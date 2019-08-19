<?php

namespace Tests\Unit\Support;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_created_response()
    {
        $response = created();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals(json_encode(['message' => 'Created']), $response->getContent());
    }

    /** @test */
    public function it_returns_an_ok_response()
    {
        $response = ok();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(json_encode(['message' => 'OK']), $response->getContent());
    }

    /** @test */
    public function it_returns_the_correct_response_message()
    {
        $this->assertEquals('Not Found', response_message(404));
    }

    /** @test */
    public function it_returns_a_correctly_formatted_status_response()
    {
        $response = status_response(404);

        $this->assertEquals(json_encode(['message' => 'Not Found']), $response->getContent());
    }

    /** @test */
    public function it_throws_an_authentication_exception_when_getting_the_current_team_if_the_user_isnt_logged_in()
    {
        $this->expectException(AuthenticationException::class);

        current_team();
    }

    /** @test */
    public function it_retrieves_the_current_team()
    {
        $this->login();

        $this->assertInstanceOf(Team::class, current_team());
    }

    /** @test */
    public function it_throws_an_authentication_exception_when_the_user_isnt_logged_in()
    {
        $this->expectException(AuthenticationException::class);

        user();
    }

    /** @test */
    public function it_retrieves_the_currently_logged_in_user()
    {
        $this->login();

        $this->assertInstanceOf(User::class, user());
    }
}

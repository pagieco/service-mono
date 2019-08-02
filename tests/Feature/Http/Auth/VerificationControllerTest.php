<?php

namespace Tests\Feature\Http\Auth;

use App\User;
use Tests\TestCase;
use App\Http\Response;
use Illuminate\Support\Str;
use Tests\ValidatesOpenAPISchema;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationControllerTest extends TestCase
{
    use RefreshDatabase;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_fails_with_a_403_exception_when_the_signature_is_expired()
    {
        $route = URL::temporarySignedRoute('verification.verify', now()->subMinute());

        $response = $this->get($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_fails_with_a_403_exception_when_the_signature_isnt_valid()
    {
        $route = URL::temporarySignedRoute('verification.verify', now()->addMinute()).Str::random();

        $response = $this->get($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_fails_with_a_404_exception_when_the_user_cant_be_found()
    {
        $route = URL::temporarySignedRoute('verification.verify', now()->addMinute());

        $response = $this->get($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_fails_with_a_400_exception_when_the_user_is_already_verified()
    {
        $user = factory(User::class)->create();

        $route = URL::temporarySignedRoute('verification.verify', now()->addMinute(), [
            'id' => $user->id,
        ]);

        $response = $this->get($route);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson(['message' => 'The user was already verified.']);
    }

    /** @test */
    public function it_successfully_verifies_the_user()
    {
        $user = factory(User::class)->state('unverified')->create();

        Event::fake();

        $route = URL::temporarySignedRoute('verification.verify', now()->addMinute(), [
            'id' => $user->id,
        ]);

        $response = $this->get($route);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson(['message' => 'The user is successfully verified.']);

        Event::assertDispatched(Verified::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }
}

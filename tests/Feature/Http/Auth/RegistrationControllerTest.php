<?php

namespace Tests\Feature\Http\Auth;

use App\User;
use Tests\TestCase;
use App\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_on_presence_of_email()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => null,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_correct_email()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => 'non-valid-email',
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_unique_email()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => $user->email,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_max_length_for_email()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => str_repeat('a', 100).faker()->email,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_presence_of_password()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => faker()->email,
            'password' => null,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_min_length_of_password()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => faker()->email,
            'password' => 'short',
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_max_length_of_password()
    {
        $response = $this->post(route('register'), [
            'name' => faker()->name,
            'email' => faker()->email,
            'password' => str_repeat('a', 260),
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_presence_of_name()
    {
        $response = $this->post(route('register'), [
            'name' => null,
            'email' => faker()->email,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_min_length_of_name()
    {
        $response = $this->post(route('register'), [
            'name' => 'abcd',
            'email' => faker()->email,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_max_length_of_name()
    {
        $response = $this->post(route('register'), [
            'name' => str_repeat('a', 110),
            'email' => faker()->email,
            'password' => faker()->password,
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_requires_a_timezone_when_registering_a_new_user()
    {
        $response = $this->post(route('register'), [
            'name' => 'My user name',
            'email' => faker()->email,
            'password' => faker()->password,
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_a_valid_timezone_when_registering_a_new_user()
    {
        $response = $this->post(route('register'), [
            'name' => 'My user name',
            'email' => faker()->email,
            'password' => faker()->password,
            'timezone' => 'fake-timezone',
        ]);

        $response->assertSchema('Register', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_register_the_user()
    {
        $this->fakeModelEvents();

        $response = $this->post(route('register'), [
            'name' => 'My user name',
            'email' => 'email@domain.com',
            'password' => 'my-secret-password',
            'timezone' => faker()->timezone,
        ]);

        $response->assertSchema('Register', Response::HTTP_CREATED);

        $user = User::find($response->json('data.id'));

        $this->assertNotNull($user);

        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->email === $user->email;
        });
    }
}

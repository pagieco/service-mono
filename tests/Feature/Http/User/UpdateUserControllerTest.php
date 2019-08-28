<?php

namespace Tests\Feature\Http\User;

use Tests\TestCase;
use App\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_validates_on_valid_email_address_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'email' => 'non-valid-email',
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_update_the_email_address()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'email' => $email = faker()->email,
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_OK);

        $this->assertEquals($this->user->email, $email);
    }

    /** @test */
    public function it_validates_on_minimum_name_length_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'name' => 'a',
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_maximum_name_length_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'name' => str_repeat('a', 101),
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_update_the_name()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'name' => 'User Name',
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_OK);

        $this->assertEquals($this->user->name, 'User Name');
    }

    /** @test */
    public function it_validates_on_correct_timezone_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'timezone' => 'My neighbours',
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_update_the_timezone()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'timezone' => $timezone = faker()->timezone,
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_OK);

        $this->assertEquals($this->user->timezone, $timezone);
    }

    /** @test */
    public function it_validates_on_minimum_password_length_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'password' => 'a',
        ]);

        $this->assertEquals($response->json('errors.password.0'), 'The password must be at least 6 characters.');

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_password_regex_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'password' => 'password',
        ]);

        $this->assertEquals($response->json('errors.password.0'), 'The password format is invalid.');

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_confirmed_password_when_updating_the_user()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'password' => 'Password1!',
        ]);

        $this->assertEquals($response->json('errors.password.0'), 'The password confirmation does not match.');

        $response->assertSchema('UpdateUser', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_can_update_the_password()
    {
        $this->login();

        $response = $this->patch(route('update-user'), [
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertSchema('UpdateUser', Response::HTTP_OK);

        $this->assertTrue(Hash::check('Password1!', $this->user->password));
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->patch(route('update-user'));
    }
}

<?php

namespace Tests\Unit\Listeners;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchUsersGravatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_wont_create_a_new_picture_when_given_an_invalid_email_address()
    {
        Storage::fake('avatars');

        $user = factory(User::class)->create();

        $this->assertNull($user->picture);

        event(new Registered($user));

        Storage::disk('avatars')->assertMissing($user->id.'.jpg');

        $this->assertNull($user->picture);
    }

    /** @test */
    public function it_saves_the_gravatar_and_updates_the_user_when_given_an_existing_email_address()
    {
        Storage::fake('avatars');

        $user = factory(User::class)->create([
            'email' => 'matt@mullenweg.com',
        ]);

        $this->assertNull($user->picture);

        event(new Registered($user));

        Storage::disk('avatars')->assertExists($user->id.'.jpg');

        $this->assertNotNull($user->picture);
    }
}

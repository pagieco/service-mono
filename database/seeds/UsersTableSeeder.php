<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * The fixed user uuid.
     *
     * @var string
     */
    public static $uuid = '1600ac87-961b-46e3-ad4d-e46aa5ff42fd';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => static::$uuid,
            'name' => 'Jelle Spekken',
            'email' => 'jspekken@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        $user->joinTeam(TeamsTableSeeder::getWildcatsTeam());
    }

    /**
     * Get an instance to the demo user.
     *
     * @return \App\User
     */
    public static function getDemoUser(): User
    {
        return User::findOrFail(static::$uuid);
    }
}

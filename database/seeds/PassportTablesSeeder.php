<?php

use Laravel\Passport\Client;
use Illuminate\Database\Seeder;

class PassportTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = Client::create([
            'user_id' => null,
            'name' => 'Password grant client',
            'secret' => config('services.passport.client.secret'),
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
        ]);

        $client->id = config('services.passport.client.id');
        $client->save();
    }
}

<?php

use App\Profile;
use Illuminate\Database\Seeder;

class ProfilesTableSeeder extends Seeder
{
    protected $profiles = [
        [
            'id' => '86f2176a-9c56-44d1-a1db-9aee9b2317e9',
            'email' => 'jspekken@gmail.com',
            'first_name' => 'Jelle',
            'last_name' => 'Spekken',
            'address_1' => 'Pastoriestraat 65',
            'address_2' => null,
            'city' => 'Eindhoven',
            'state' => 'Noord-Brabant',
            'zip' => '5612EJ',
            'country' => 'Netherlands',
            'phone' => '06-14979312',
            'timezone' => 'Europe/Amsterdam',
            'tags' => [],
            'custom_fields' => [],
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domain = DomainsTableSeeder::getDemoDomain();

        foreach ($this->profiles as $data) {
            $profile = new Profile($data);
            $profile->domain()->associate($domain);
            $profile->save();
        }
    }
}

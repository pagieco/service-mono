<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Domain;
use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    $domain = factory(Domain::class)->create([
        'team_id' => $team->id,
    ]);

    return [
        'team_id' => $team->id,
        'domain_id' => $domain->id,
        'email' => $faker->email,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address_1' => $faker->address,
        'address_2' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->citySuffix,
        'zip' => $faker->postcode,
        'country' => $faker->country,
        'phone' => $faker->phoneNumber,
        'timezone' => $faker->timezone,
        'tags' => [],
        'custom_fields' => [],
    ];
});

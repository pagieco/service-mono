<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use Faker\Generator as Faker;

$factory->define(App\Role::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    $name = $faker->domainName;

    return [
        'team_id' => $team->id,
        'name' => $name,
        'description' => $faker->text,
    ];
});

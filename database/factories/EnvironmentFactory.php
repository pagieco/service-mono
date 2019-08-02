<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Environment;
use Faker\Generator as Faker;

$factory->define(Environment::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    return [
        'team_id' => $team->id,
        'name' => $faker->domainName,
    ];
});

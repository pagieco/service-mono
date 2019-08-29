<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Domain;
use App\Environment;
use Faker\Generator as Faker;

$factory->define(Domain::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    $environment = factory(Environment::class)->create();

    return [
        'team_id' => $team->id,
        'environment_id' => $environment->id,
        'domain_name' => $faker->domainName,
        'api_token' => (new Domain)->generateApiToken(),
    ];
});

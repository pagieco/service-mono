<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Domain;
use App\Collection;
use Faker\Generator as Faker;

$factory->define(Collection::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    $domain = factory(Domain::class)->create([
        'team_id' => $team->id,
    ]);

    return [
        'team_id' => $team->id,
        'domain_id' => $domain->id,
        'name' => $faker->name,
    ];
});

<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Workflow;
use Faker\Generator as Faker;

$factory->define(Workflow::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    return [
        'team_id' => $team->id,
        'name' => $faker->name,
        'description' => $faker->text,
    ];
});

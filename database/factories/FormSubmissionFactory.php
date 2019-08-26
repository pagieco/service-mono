<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Form;
use App\Team;
use App\Domain;
use App\FormSubmission;
use Faker\Generator as Faker;

$factory->define(FormSubmission::class, function (Faker $faker) {
    $team = factory(Team::class)->create();

    $domain = factory(Domain::class)->create([
        'team_id' => $team->id,
    ]);

    $form = factory(Form::class)->create([
        'team_id' => $team->id,
        'domain_id' => $domain->id,
    ]);

    return [
        'team_id' => $team->id,
        'domain_id' => $domain->id,
        'form_id' => $form->id,
        'submission_data' => [],
    ];
});

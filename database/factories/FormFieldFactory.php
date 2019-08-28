<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Form;
use App\Team;
use App\Domain;
use App\FormField;
use Illuminate\Support\Str;
use App\Enums\FormFieldType;
use Faker\Generator as Faker;

$factory->define(FormField::class, function (Faker $faker) {
    $name = $faker->name;
    $types = FormFieldType::getValues();

    return [
        'team_id' => factory(Team::class)->create(),
        'domain_id' => factory(Domain::class)->create(),
        'form_id' => factory(Form::class)->create(),
        'display_name' => $name,
        'slug' => Str::slug($name),
        'type' => $types[array_rand($types)],
    ];
});

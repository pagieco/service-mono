<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Permission::class, function (Faker $faker) {
    $name = $faker->domainName;

    return [
        'slug' => Str::slug($name),
        'name' => $name,
        'description' => $faker->text,
    ];
});

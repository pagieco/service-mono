<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Font;
use Faker\Generator as Faker;

$factory->define(Font::class, function (Faker $faker) {
    $variants = ['regular', 'italic', 100, 200, 300, 400, 500];
    $subsets = ['latin', 'latin-ext', 'vietnamese', 'korean'];

    return [
        'origin' => 'google-fonts',
        'family' => $faker->name,
        'variants' => [$variants[array_rand($variants)]],
        'subsets' => [$subsets[array_rand($subsets)]],
    ];
});

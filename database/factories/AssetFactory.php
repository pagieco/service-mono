<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use App\Asset;
use App\Domain;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Asset::class, function (Faker $faker) {
    Storage::fake();

    $team = factory(Team::class)->create();

    $domain = factory(Domain::class)->create([
        'team_id' => $team->id,
    ]);

    $filename = $faker->word;
    $extension = $faker->fileExtension;

    $path = UploadedFile::fake()
        ->image('fake-image.jpg')
        ->storePubliclyAs($team->id, 'fake-image.jpg');

    return [
        'hash' => 123,
        'team_id' => $team->id,
        'domain_id' => $domain->id,
        'filename' => $filename,
        'extension' => $extension,
        'mimetype' => $faker->mimeType,
        'filesize' => $faker->randomNumber(),
        'path' => $path,
    ];
});

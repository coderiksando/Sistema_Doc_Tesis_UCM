<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Fit;
use App\ArchivoPdf;
use Faker\Generator as Faker;

$factory->define(ArchivoPdf::class, function (Faker $faker) {
    return [
        'id_fit' => Fit::all()->random()->id, 
        'path' => $faker->file('public/storage/pdfTest', 'public/storage/users'),
        'filename' => $faker->uuid, 
        'tipo_pdf' => $faker->randomElement(['acta','avance_t','final_t']), 
        ];
});

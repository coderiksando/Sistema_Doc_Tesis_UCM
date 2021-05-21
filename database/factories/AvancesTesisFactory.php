<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\ArchivoPdf;
use App\Fit;
use App\AvancesTesis;
use Faker\Generator as Faker;

$factory->define(AvancesTesis::class, function (Faker $faker) {
    return [
        'id_tesis' => Fit::all()->random()->id, 
        'id_archivo' => ArchivoPdf::all()->random()->id,
        'descripcion' => $faker->sentence(10)
    ];
});

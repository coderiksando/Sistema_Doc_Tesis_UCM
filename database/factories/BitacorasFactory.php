<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Fit;
use App\Bitacoras;
use Faker\Generator as Faker;

$factory->define(Bitacoras::class, function (Faker $faker) {
    return [
        'id_tesis' => Fit::all()->random()->id, 
        'comentario' => $faker->sentence(3),
        'acuerdo' => $faker->sentence(10),
        'fecha'=> $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});

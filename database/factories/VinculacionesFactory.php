<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Vinculaciones;
use Faker\Generator as Faker;

$factory->define(Vinculaciones::class, function (Faker $faker) {
    return [
        'tipo' => $faker->randomElement(['Fondo Concursable', 'Empresa', 'Comunidad']), 
        'nombre' => $faker->company, 
        'descripcion' => $faker->sentence(4),
        'estado' => $faker->randomElement(array('A','I'))
    ];
});

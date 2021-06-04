<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Fit;
use App\Roles;
use Faker\Generator as Faker;

$factory->define(Fit::class, function (Faker $faker) {
    return [
        'id_vinculacion' => $faker->numberBetween(1, 21),
        'id_p_guia' => Roles::find(3)->Users_Roles->random()->id_user,
        'id_p_co_guia' => Roles::find(3)->Users_Roles->random()->id_user,
        'estado' => $faker->randomElement(array('A','R','D')),
        'titulo' => $faker->sentence(3),
        'tipo' => 'Tesis',
        'objetivo' => $faker->sentence(10),
        'descripcion' => $faker->sentence(10),
        'contribucion' => $faker->sentence(10),
        'aprobado_pg' => $faker->randomElement(array('A','R','P'))
    ];
});

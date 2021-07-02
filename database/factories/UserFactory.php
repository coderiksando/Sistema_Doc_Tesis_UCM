<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'rut' => $faker->unique()->randomNumber(8, true),
        'nombres' => $faker->firstName,
        'apellidos' => $faker->lastName,
        'id_escuela' => $faker->numberBetween(1,7),
        'email' => $faker->unique()->randomNumber(5, false) . '@gmail.com',
        'password' => bcrypt('12345'),
        'remember_token' => Str::random(10),
        'state' => $faker->randomElement(array('A','I')),
        'birthday' => $faker->date('Y-m-d'),
        'telefono' => $faker->randomNumber(8, true),
        'direccion' => $faker->address,
        'grado_academico' => $faker->colorName,
        'f_ingreso' => $faker->date('Y-m-d'),
        'f_salida' => $faker->date('Y-m-d')
    ];
});

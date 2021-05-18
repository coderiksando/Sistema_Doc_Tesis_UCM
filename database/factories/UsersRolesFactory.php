<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Users_Roles;
use Faker\Generator as Faker;

$factory->define(Users_Roles::class, function (Faker $faker) {
    return [
        'id_user' => '1',
        'id_roles' => $faker->numberBetween(1, 6)
    ];
});

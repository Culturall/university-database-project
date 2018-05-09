<?php

use Faker\Generator as Faker;

$factory->define(App\Worker::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        // 'password' => $faker->md5,
        'password' => Hash::make('password'),
        'birthdate' => $faker->date,
        'email' => $faker->unique()->email,
        'requester' => $faker->boolean
    ];
});

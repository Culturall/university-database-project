<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->words($nb = 3, true),
        'description' => $faker->realText($maxNbChars = 1000),
        'campaign' => App\Campaign::inRandomOrder()->get()->first()->id
    ];
});

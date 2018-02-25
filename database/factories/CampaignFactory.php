<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Model::class, function (Faker $faker) {
    $year = rand(2009, 2016);
    $month = rand(1, 12);
    $day = rand(1, 28);
    $date = Carbon::create($year,$month ,$day);

    return [
        'title' => $faker->unique()->firstName,
        'description' => $faker->text,
        'opening_date' => $date->format('Y-m-d'),
        'closing_date' => $date->addWeeks(rand(1, 52))->format('Y-m-d'),
        'sign_in_period_open' => null,
        'sign_in_period_close' => null,
        'required_workers' => $faker->numberBetween(1, 200),
        'threshold_percentage' => $faker->numberBetween(0, 100)
    ];
});

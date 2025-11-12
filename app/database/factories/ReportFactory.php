<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Report;
use Faker\Generator as Faker;

$factory->define(Report::class, function (Faker $faker) {
    // $faker = \Faker\Factory::create('ja_JP');

    return [
        'user_id'    => $faker->numberBetween(1, 50),
        'event_id'   => $faker->numberBetween(1, 50),
        'comment'    => $faker->realText(80),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'user_id'      => $faker->numberBetween(1, 10), // 存在するユーザーIDを想定
        'title'        => $faker->sentence(3),
        'date'         => $faker->date(),
        'start_time'   => $faker->time('H:i:s'),
        'end_time'     => $faker->time('H:i:s'),
        'format'       => $faker->randomElement(['online', 'offline']),
        'capacity'     => $faker->numberBetween(5, 100),
        'description'  => $faker->realText(100),
        'image_path'   => null,
        'del_flg'      => 0,
        'created_at'   => now(),
        'updated_at'   => now(),
    ];
});
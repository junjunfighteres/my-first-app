<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bookmark;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'user_id'    => $faker->numberBetween(1, 50),
        'event_id'   => $faker->numberBetween(1, 50),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
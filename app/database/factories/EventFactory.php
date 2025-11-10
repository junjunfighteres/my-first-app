<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    // role=1（主催者）のユーザーが存在すればそこから選択
    $hostUser = User::where('role', 1)->inRandomOrder()->first();

    return [
        'user_id'     => $hostUser ? $hostUser->id : 1,
        'title'       => $faker->sentence(3),
        'date'        => $faker->date(),
        'start_time'  => $faker->time('H:i:s'),
        'end_time'    => $faker->time('H:i:s'),
        'format'      => $faker->randomElement(['Twitch', 'YouTube', 'その他']),
        'capacity'    => $faker->numberBetween(10, 100),
        'description' => $faker->realText(100),
        'image_path'  => null,
        'del_flg'     => 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ];
});
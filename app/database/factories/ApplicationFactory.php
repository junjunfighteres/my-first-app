<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Application;
use Faker\Generator as Faker;

$factory->define(Application::class, function (Faker $faker) {
    // Fakerを日本語にする場合は以下を使ってください（必要なら）
    // $faker = \Faker\Factory::create('ja_JP');

    return [
        'user_id'    => $faker->numberBetween(1, 50), // 実際のユーザー数に合わせて調整
        'event_id'   => $faker->numberBetween(1, 50), // 実際のイベント数に合わせて調整
        'comment'    => $faker->realText(100),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
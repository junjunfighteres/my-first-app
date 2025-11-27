<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    // 🔥 ここにプロフィール画像ファイルを追加
    $avatars = [
        'avatars/avatar1.jpg',
        'avatars/avatar2.jpg',
        'avatars/avatar3.jpg',
        'avatars/avatar4.jpg',
        'avatars/avatar5.jpg',
    ];

    return [
        'email'                => $faker->unique()->safeEmail,
        'name'                 => $faker->name,
        'password'             => bcrypt('password'),
        'role'                 => 0,
        'del_flg'              => 0,

        // 🔥 ランダムでプロフィール画像を割り当て
        'profile_image_path'   => $faker->randomElement($avatars),

        'self_introduction'    => $faker->realText(50),
        'password_reset_token' => Str::random(10),
        'created_at'           => now(),
        'updated_at'           => now(),
    ];
});
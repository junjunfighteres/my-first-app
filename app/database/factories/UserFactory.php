<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email'                => $faker->unique()->safeEmail,
        'name'                 => $faker->name,
        'password'             => bcrypt('password'),
        'role'                 => 0,
        'del_flg'               => 0,
        'profile_image_path'   => null,
        'self_introduction'    => $faker->realText(50),
        'password_reset_token' => Str::random(10),
        'created_at'           => now(),
        'updated_at'           => now(),
    ];
});
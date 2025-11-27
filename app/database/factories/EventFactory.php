<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {

    // 主催者ユーザー（role=1）がいれば使用、いなければ1番ユーザー
    $hostUser = User::where('role', 1)->inRandomOrder()->first();

    // サンプル画像
    $sampleImages = [
        'events/event1.jpg',
        'events/event2.jpg',
        'events/event3.jpg',
        'events/event4.jpg',
        'events/event5.jpg',
    ];

    return [
        'user_id'     => $hostUser ? $hostUser->id : 1,
        'title'       => $faker->sentence(3),

        // 🔥 単日イベントなので date を使う！
        'date'        => $faker->date(),
        'start_time'  => $faker->time('H:i:s'),
        'end_time'    => $faker->time('H:i:s'),

        // 🔥 format をカテゴリとして使う
        'format'      => $faker->randomElement([
            'meeting',   // オンラインミーティング
            'seminar',   // セミナー
            'workshop',  // ワークショップ
            'sports',    // スポーツイベント
            'party',     // 交流会
        ]),

        'capacity'    => $faker->numberBetween(10, 100),
        'description' => $faker->realText(100),

        // 🔥 ランダム画像
        'image_path'  => $faker->randomElement($sampleImages),

        'del_flg'     => 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ];
});
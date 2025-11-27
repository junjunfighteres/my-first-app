<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ⭐ 管理者ユーザーを 1 名作成
        User::create([
            'email'                => 'admin@example.com',
            'name'                 => '管理者',
            'password'             => Hash::make('password'),
            'role'                 => 2,        // 管理者
            'del_flg'              => 0,
            'profile_image_path'   => null,
            'self_introduction'    => '管理者アカウントです。',
            'password_reset_token' => null,
        ]);

        // ⭐ 一般ユーザー 15 名（Factory利用）
        factory(User::class, 15)->create();
    }
}

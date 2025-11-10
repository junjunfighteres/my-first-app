<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 管理者を1人作成
        $admin = factory(App\Models\User::class)->create([
            'name'  => '管理者ユーザー',
            'email' => 'admin@example.com',
            'role'  => 2, // 管理者
        ]);

        // 主催者ユーザーを数名
        $hosts = factory(App\Models\User::class, 3)->create(['role' => 1]);

        // 一般ユーザー
        factory(App\Models\User::class, 10)->create();

        // イベント（role=1 のユーザーから紐付け）
        factory(App\Models\Event::class, 20)->create();
    }
}
<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@example.com',
                'name' => '管理ユーザー',
                'password'=> bcrypt('password'),
                'role' => 2,
                'del_flg' => 0, 
                'profile_image_path' => null,
                'self_introduction' => '管理者として登録',
                'password_reset_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'email' => 'user1@example.com',
                'name' => 'ユウジ',
                'password'=> bcrypt('password'),
                'role' => 0,
                'del_flg' => 0, 
                'profile_image_path' => null,
                'self_introduction' => 'ユーザーとして登録',
                'password_reset_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
    ]);
    }
}

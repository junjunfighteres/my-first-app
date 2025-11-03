<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('applications')->insert([
            [
                'user_id' => 2,
                'event_id' => 1,
                'comment' => '参加させていただきます！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'event_id' => 2,
                'comment' => '当日よろしくお願いします。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
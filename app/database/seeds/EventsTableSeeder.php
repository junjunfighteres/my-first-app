<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'user_id' => 1,
                'title' => 'Laravel勉強会',
                'date' => '2025-11-10',
                'start_time' => '19:00:00',
                'end_time' => '20:00:00',
                'format' => 'online',
                'capacity' => 50,
                'description' => '初心者向けのLaravel勉強会です。',
                'image_path' => null,
                'del_flg' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'オフライン交流会',
                'date' => '2025-11-15',
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'format' => 'offline',
                'capacity' => 20,
                'description' => '弁当でます',
                'image_path' => null,
                'del_flg' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
    ]);
    }
}

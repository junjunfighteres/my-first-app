<?php

use Illuminate\Database\Seeder;
use App\Models\Bookmark;
use App\Models\User;
use App\Models\Event;

class BookmarkSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $events = Event::all();

        foreach ($users as $user) {
            // ランダムにイベントをブックマーク（重複しないように）
            $eventIds = $events->random(rand(1, 3))->pluck('id')->toArray();

            foreach ($eventIds as $eventId) {
                Bookmark::firstOrCreate([
                    'user_id' => $user->id,
                    'event_id' => $eventId,
                ]);
            }
        }
    }
}
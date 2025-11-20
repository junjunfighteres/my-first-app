<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Application;
use App\Models\Report;

class Event extends Model

{
    protected $table = 'events';

    protected $fillable = [
        'user_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'format',
        'capacity',
        'status',
        'description',
        'image_path',
        'del_flg',
    ];

    public $timestamps = true;

    // 主催者（User）とのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 参加申込とのリレーション
    public function applications()
    {
        return $this->hasMany(Application::class, 'event_id');
    }

    // 違反報告とのリレーション
    public function reports()
    {
        return $this->hasMany(Report::class, 'event_id');
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(
            Event::class,
            'applications', // 中間テーブル名
            'user_id',      // users.id → applications.user_id
            'event_id'      // applications.event_id → events.id
        );
    }
}
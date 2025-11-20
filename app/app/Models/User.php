<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use Notifiable;
    
    protected $table = 'users';

    protected $fillable = [
        'email', 
        'name', 
        'password', 
        'role', 
        'del_flg',
        'avatar_path',
        'self_introduction',
    ];

    public $timestamps = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(
            Event::class,
            'applications',
            'user_id',
            'event_id'
        );
    }
    
}

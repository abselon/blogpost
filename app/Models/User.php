<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];



    // protected function avatar(): Attribute {
    //     return Attribute::make(get: function($value) {
    //         return $value ? '/storage/avatars' . $value : '/fallback-avatar.jpg';
    //     });

    // }  THIS IS FOR LARAVEL 9 USE, DOES NOT WORK IN LARAVEL 8


    protected function getAvatarAttribute($value)
    {
        return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function feedPosts()
    {
        return $this->hasManyThrough(Post::class, Follow::class, 'users_id', 'users_id', 'id', 'followeduser');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followeduser'); //can also add 'id'
    }

    public function followingTheseUsers()
    {
        return $this->hasMany(Follow::class, 'users_id'); //can also add 'id'
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'users_id');
    }
}

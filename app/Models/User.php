<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function feedPosts()
    {
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(get: function($value) {
            return $value ? asset('storage/avatars/' . $value) : asset('/fallback-avatar.jpg');
        });
    }

    public function posts() 
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followeduser');
    }

    public function followingTheseUsers()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'isAdmin' => $this->isAdmin 
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    protected static function booted()
    {
        static::created(function ($user) {
            try {
                Http::timeout(5)->post('http://localhost:' . env('NODE_SERVER_PORT', 5001) . '/create-mongodb-user', [
                    'sql_id' => $user->id,
                    'username' => $user->username,
                    'avatar' => 'fallback-avatar.jpg',
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create MongoDB user: ' . $e->getMessage());
            }
        });
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
}

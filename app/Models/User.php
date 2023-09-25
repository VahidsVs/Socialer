<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
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
    protected function avatar():Attribute//-->>> name of function should be as same as the column name in database
    {
        return Attribute::make(get: function($value){
            return $value ? "/storage/avatar/$value" : "/fallback-avatar.jpg";
        });

        
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
        'password' => 'hashed',
    ];
    public function feedPost() //---->name of fucntion doesnt matter!
    {
        return $this->hasManyThrough(Post::class,Follow::class,"user_id","user_id","id","followed_user");
    }
    public function followers() //---->name of fucntion doesnt matter!
    {
        return $this->hasMany(Follow::class,"followed_user");
    }
    public function followings() //---->name of fucntion doesnt matter!
    {
        return $this->hasMany(Follow::class,"user_id");
    }
    public function posts() //---->name of fucntion doesnt matter!
    {
        return $this->hasMany(Post::class,"user_id");
    }
}
 
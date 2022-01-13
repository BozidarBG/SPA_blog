<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

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

    public $avatars_folder="images/avatars/";
    public $defaults_folder="images/defaults/";

    public function articles(){
        return $this->hasMany(Article::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function isAdmin(){
        return $this->role===1;
    }
    public function banned(){
        return $this->hasMany(BannedUser::class, 'user_id')->withTrashed();
    }

    public function sendPasswordResetNotification($token)
    {
        //frontend client
        $url = env('FRONTEND_URL').'/v1/reset-password?token=' . $token;
        //dd($url);//http://spablog.test/v1/reset-password?token=c041343209350a93bb6c07244ed38d419d2f1ff13ebbf2f59be4386507ef0642
        $this->notify(new ResetPasswordNotification($url));
    }

}

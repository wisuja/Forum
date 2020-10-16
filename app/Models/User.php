<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'confirmed',
        'confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadsCacheKey($thread)
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {
        cache()->forever($this->visitedThreadsCacheKey($thread), Carbon::now());
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function setNameAttribute($name) {
        $this->attributes['name'] = preg_replace('/\s/','', $name);
    }

    public function getAvatarPathAttribute($avatar)
    {
        $avatar = $avatar ?: 'avatars/default.jpg';
        return asset('/storage/' .  $avatar);
    }

    public function confirm() 
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['admin']);
    }
}

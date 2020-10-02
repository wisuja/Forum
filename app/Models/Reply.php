<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;
    use RecordActivity;
    use Favoritable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($reply) {
            $reply->favorites->each->delete();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function path()
    {
        return $this->thread->path() . "#reply_{$this->id}";
    }
}

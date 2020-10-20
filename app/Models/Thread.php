<?php

namespace App\Models;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use HasFactory;
    use RecordActivity;
    use Searchable;

    protected $guarded = [];

    protected $with = ['channel', 'creator'];

    protected $withCount = ['replies'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function($thread){
            $thread->update(['slug' => $thread->title]);
        }); 
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value) 
    {
        $slug = Str::slug($value);

        if(static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        $this->touch();

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
            'thread_id' => $this->id
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)
    {
        $user = $user ?: auth()->user();

        $key = $user->visitedThreadsCacheKey($this);
        return $this->updated_at > cache($key);
    }

    public function setBestReply($reply) 
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}

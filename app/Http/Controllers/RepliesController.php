<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReplyRequest;
use App\Models\Reply;
use App\Models\Thread;
use Exception;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    private $limit;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->limit = 2000;
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelSlug, Thread $thread, CreateReplyRequest $req)
    {
        try {
            if($thread->locked) {
                return response('Thread is locked', 422);
            }
    
            if(request()->hasFile('image')) {
                request()->validate(['image' => ['image']]);
    
                $reply = $thread->addReply([
                    'body' => request('body'),
                    'user_id' => auth()->id(),
                    'image_path' => request()->file('image')->store('images', 'public')
                ]);
            } else {
                $reply = $thread->addReply([
                    'body' => request('body'),
                    'user_id' => auth()->id()
                ]);
            }
    
            tap($thread->fresh(), function ($thread) {
                if($thread->replies_count > $this->limit) {
                    $thread->update(['locked' => true]);
                }
            });
            $reply = $reply->load('owner');
    
            $reply->isThreadLocked = $thread->fresh()->locked;
    
            return $reply;
        } catch(Exception $ex) {
            return response('Sorry, your reply could not be saved at this moment.', 422);
        }
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate([
                'body' => ['required', 'spamfree']
            ]);

            if(request()->hasFile('image')) {
                request()->validate([
                    'image' => ['image'],
                ]);

                $reply->update([
                    'body' => request('body'), 
                    'image_path' => request()->file('image')->store('images', 'public')
                ]);

                return $reply;
            }

            $reply->update(['body' => request('body')]);

            return $reply;
        } catch (Exception $ex) {
            return response('Sorry, your reply could not be saved at this moment.', 422);
        }
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted.']);
        }

        return back();
    }
}

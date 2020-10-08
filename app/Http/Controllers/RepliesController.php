<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Exception;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelSlug, Thread $thread)
    {
        try {
            $this->validate(request(), [
                'body' => ['required', 'spamfree']
            ]);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (Exception $ex) {
            return response('Sorry, your reply could not be saved at this moment.', 422);
        }

        return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), [
                'body' => ['required', 'spamfree']
            ]);

            $reply->update(['body' => request('body')]);
        } catch (Exception $ex) {
            // throw $ex;
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

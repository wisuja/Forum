<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChannelsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');    
    }

    public function store() 
    {
        if(!auth()->user()->isAdmin()) {
            return response('A user can not create channels', 403);
        }

        request()->validate([
            'name' => ['required', 'spamfree', 'max:50']
        ]);

        Channel::create([
            'name' => request('name'),
            'slug' => Str::slug(request('name'))
        ]);

        Cache::forget('channels');

        if(request()->expectsJson()) {
            return response('Successfully create a channel', 201);
        }

        return back();
    }
}

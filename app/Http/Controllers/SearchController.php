<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show() 
    {
        $threads = Thread::search(request('q'))->paginate(25);

        if(request()->expectsJson()) 
        {
            return $threads;
        }

        return view('threads.search', compact('threads'));
    }
}

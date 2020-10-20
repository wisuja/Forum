<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show() 
    {
        $query = request('q');

        if(request()->expectsJson()) 
        {
            return Thread::search($query)->paginate(25);
        }

        return view('threads.search', compact('query'));
    }
}

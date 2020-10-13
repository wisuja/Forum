<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class RegisterConfirmationsController extends Controller
{
    public function index() 
    {
        $user = User::where('confirmation_token', request('token'))->first();
        
        if (!$user) return redirect(route('threads'))->with('flash', 'Sorry, unknown token');
        
        $user->confirm();

        return redirect(route('threads'))
            ->with('flash', 'Your account is now confirmed!');
    }
}

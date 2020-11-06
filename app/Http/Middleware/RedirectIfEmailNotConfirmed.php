<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfEmailNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user()->confirmed) 
        {
            if($request->expectsJson()) {
                return response('You must first confirm your email address.', 403);
            }
            
            return redirect('/threads')->with('flash', 'You must first confirm your email address.');
        }

        return $next($request);
    }
}

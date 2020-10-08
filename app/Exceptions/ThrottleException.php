<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

class ThrottleException extends Exception
{
    public function report()
    {
        throw new HttpResponseException(response()->json("You are posting too frequently. Please take a break.", 429));
    }

    public function render($request)
    {
        
    }
}

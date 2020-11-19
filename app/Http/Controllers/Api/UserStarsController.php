<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserStarsController extends Controller
{
    private $stars_given_limit;

    public function __construct()
    {
        $this->middleware('auth');

        $this->stars_given_limit = 3;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        if($user->name == auth()->user()->name) 
            return response("You can't star yourself!", 403);

        if(auth()->user()->stars_given >= $this->stars_given_limit) {
            if(auth()->user()->updated_at < Carbon::now()->subDay()) {
                auth()->user()->update([
                    'stars_given' => 0
                ]);
            } else {
                return response("You reach the limit of stars given in a day", 403);
            }
        }

        auth()->user()->increment('stars_given');
        auth()->user()->touch();
        
        $user->increment('stars');

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

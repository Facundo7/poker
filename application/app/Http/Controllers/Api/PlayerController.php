<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Player::where('tournament_id', $request->tournamentid)->join('users', 'users.id', '=', 'players.user_id')->get();
        //return $request;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $tournament=Tournament::find($request->tournament_id);
         $number_of_players=Player::where('tournament_id',$request->tournament_id)->count();
         if((Player::where([['tournament_id',$request->tournament_id],['user_id',Auth::id()]])->count()==0)&&($tournament->players_number>$number_of_players)){
         $player = new Player;
         $player->tournament_id=$tournament->id;
         $player->sit=$number_of_players+1;
         $player->stack=$tournament->initial_stack;
         $player->user_id=Auth::id();
         $player->save();
         return '200';
         }else return '500';

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        //
    }

    public function logged($tournament_id){

        return Player::where([['user_id',Auth::id()],['tournament_id',$tournament_id]])->first();

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tournament::where('active',true)->withCount('players')->get()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        return $tournament->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        //
    }

    public function boardCards(Tournament $tournament){

        return $tournament->currentRound->boardCards;

    }

    public function currentRound(Tournament $tournament){

        return $tournament->currentRound;

    }

    public function currentBetRound(Tournament $tournament){

        $bet_round=$tournament->currentRound;
        if($bet_round)
        return $bet_round->currentBetRound;
    }

    public function playerLogged(Tournament $tournament){

        return $tournament->playerLogged()->with(['cards' => function($query) use ($tournament){

            $query->where('round_id', $tournament->currentRound->id);

        }])->first();

    }

    public function players(Tournament $tournament){

        return $tournament->players;

    }
}

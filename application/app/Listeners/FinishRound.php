<?php

namespace App\Listeners;

use App\Events\RoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\Game;
use App\Facades\Evaluation;

class FinishRound
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RoundFinished  $event
     * @return void
     */
    public function handle(RoundFinished $event)
    {

        $tournament=$event->round->tournament;
        $event->round->current=false;
        $event->round->save();

        if($tournament->playingPlayers()->count()==1)
        {
            $tournament->playingPlayers[0]->stack+=$event->round->pot;
            $tournament->playingPlayers[0]->save();
        }else {

            Evaluation::evaluateCards($tournament->playingPlayers()->with(['cards' => function($query) use($tournament){
                $query->where('round_id', $tournament->currentRound->id);
            }])->get(), $event->round->boardCards, $event->round);
        }

        //Game::killPlayers($tournament);
        if($tournament->alivePlayers()->count()==1){
            //finish tournament
        }else{
        Game::changeButton($tournament);
        Game::createRound($tournament);
        }
    }
}

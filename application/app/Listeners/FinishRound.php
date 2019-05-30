<?php

namespace App\Listeners;

use App\Events\RoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\Game;
use App\Facades\Evaluation;
use App\Events\ShowDown;

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

        $players=$tournament->playingPlayers()->with(['user','cards' => function($query) use($tournament){
            $query->where('round_id', $tournament->currentRound->id);
        }])->get();


        if(count($players)==1)
        {
            $players[0]->stack+=$event->round->pot;
            $players[0]->save();
        }else {

            event(new ShowDown($players));
            Evaluation::evaluateCards($players, $event->round->boardCards, $event->round);
        }


        //$event->round->current=false;
        //$event->round->save();
        Game::changeButton($tournament);
        Game::killPlayers($tournament);
        if($tournament->alivePlayers()->count()==1){
            //finish tournament
        }else{
            //Game::createRound($tournament);
        }
    }
}

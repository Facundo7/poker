<?php

namespace App\Listeners;

use App\Events\RoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\Game;

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
            //give pot to the player left
        }else {

            Game::evaluateCards($tournament->playingPlayers()->with('cards')->get(), $event->round->boardCards, $event->round);
        }

        //kill players
        Game::changeButton($tournament);
        Game::createRound($tournament);
    }
}

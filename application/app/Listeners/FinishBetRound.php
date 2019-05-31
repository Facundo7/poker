<?php

namespace App\Listeners;

use App\Events\BetRoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\Game;
use App\Events\RoundFinished;
use App\Facades\BetRoundTool;

class FinishBetRound
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
     * @param  BetRoundFinished  $event
     * @return void
     */
    public function handle(BetRoundFinished $event)
    {
        $bet_round=$event->bet_round;
        $tournament=$bet_round->round->tournament;
        Game::updatePotAndStack($tournament);
        $event->bet_round->current=false;
        $event->bet_round->save();

        if($tournament->playingPlayers()->count()==1)
        {

            event(new RoundFinished($tournament->currentRound));

        }else if($bet_round->bet_phase<3){
            //not river
            BetRoundTool::createBetRound($bet_round->round, $bet_round->bet_phase+1);
        }else{
            //river
            event(new RoundFinished($tournament->currentRound));
        }
    }
}

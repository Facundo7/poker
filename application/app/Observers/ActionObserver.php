<?php

namespace App\Observers;

use App\Models\Action;
use App\Events\NewAction;
use App\Facades\Game;
use App\Events\BetRoundFinished;

class ActionObserver
{
    /**
     * Handle the action "created" event.
     *
     * @param  \App\Models\Action  $action
     * @return void
     */
    public function created(Action $action)
    {

        Game::updatePlayer($action);

        event(new NewAction($action));

        //check if bet round finished
        $bet_round=$action->betRound;
        $tournament=$bet_round->round->tournament;

        if($tournament->playingPlayers>1){


            if($bet_round->players==$bet_round->actions()->count()&&$tournament->playingPlayers()->distinct('betting')->count()==1){

                event(new BetRoundFinished($bet_round));
            }else{

                Game::nextTurn($tournament);
            }

        }else {

            event(new BetRoundFinished($bet_round));
        }



    }

    /**
     * Handle the action "updated" event.
     *
     * @param  \App\Models\Action  $action
     * @return void
     */
    public function updated(Action $action)
    {
        //
    }

    /**
     * Handle the action "deleted" event.
     *
     * @param  \App\Models\Action  $action
     * @return void
     */
    public function deleted(Action $action)
    {
        //
    }

    /**
     * Handle the action "restored" event.
     *
     * @param  \App\Models\Action  $action
     * @return void
     */
    public function restored(Action $action)
    {
        //
    }

    /**
     * Handle the action "force deleted" event.
     *
     * @param  \App\Models\Action  $action
     * @return void
     */
    public function forceDeleted(Action $action)
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\Action;
use App\Events\NewAction;
use App\Facades\Game;
use App\Events\BetRoundFinished;
use App\Models\Tournament;

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

        $tournament=$action->betRound->round->tournament;
        $bet_round=$action->betRound;
        Game::nextTurn($tournament);
        Game::updatePlayer($action);

        event(new NewAction($action));

        //check if bet round finished


        if($tournament->playingPlayers()->count()>1){


            if($bet_round->players<=$bet_round->actions()->count()&&$tournament->playingPlayers()->distinct()->count('betting')==1){

                event(new BetRoundFinished($bet_round));
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

<?php

namespace App\Observers;

use App\Models\Action;
use App\Events\NewAction;
use App\Facades\Game;
use App\Events\BetRoundFinished;
use App\Models\Tournament;
use App\Facades\BetRoundTool;

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




        BetRoundTool::nextTurn($tournament);
        Game::updatePlayer($action);




        //check if bet round finished

        if($tournament->playingPlayers()->count()>1){

            if($tournament->playingPlayers()->where('stack', ">", 0)->whereColumn('stack','<>','betting')->count()>=1)
            {
                $notAllinBetting=$tournament->playingPlayers()->where('stack', ">", 0)->whereColumn('stack','<>','betting')->max('betting');
            }else
            {
                $notAllinBetting=0;
            }

            if($tournament->playingPlayers()->where('stack', ">", 0)->whereColumn('stack','=','betting')->count()>=1)
            {
                $allinBetting=$tournament->playingPlayers()->where('stack', ">", 0)->whereColumn('stack','=','betting')->max('betting');
            }else
            {
                $allinBetting=0;
            }


            if($bet_round->players<=$bet_round->actions()->count()&&
            $tournament->playingPlayers()->where('stack', ">", 0)->whereColumn('stack','<>','betting')->distinct()->count('betting')<=1&&(
            $notAllinBetting>=$allinBetting||$notAllinBetting==0))
            {
                event(new BetRoundFinished($bet_round));
            }

        }else {

            event(new BetRoundFinished($bet_round));
        }

        event(new NewAction($action));
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

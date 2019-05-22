<?php

namespace App\Observers;

use App\Models\Action;
use App\Events\NewAction;
use App\Facades\Game;

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
        //0-Check, 1-call, 2-raise, 3-reraise, 4-fold

        Game::updatePlayers($action);

        event(new NewAction($action));




        //check if bet round finished

        // if($bet_round->bet_phase==0){
        //         //this is preflop
        //     $check_id=$bb_id;
        // }else{
        //         //not preflop
        //     $check_id=$button_id;
        // }

        //     if($bet_round->actions()->where('user_id',$check_id)->count()>0&&$tournament->players()->where('playing',true)->distinct('betting')>1){

        // }


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

<?php

namespace App\Observers;

use App\Models\Action;
use App\Events\NewAction;

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

        $bet_round=$action->betRound;
        $round=$bet_round->round;
        $tournament=$round->tournament;
        $players=$tournament->players()->where('playing',true)->orderBy('sit')->get();
        $button_id=$bet_round->round->tournament->players()->where('button',true)->first()->id;
        $bb_id=$bet_round->round->tournament->players()->where('bb',true)->first()->id;


        //update player
        $player=$action->player;
        if($action->action==4){
            //fold
            $player->update(["playing"=>false]);
            $player->save();

        }else if($action->action!=0){
            //call, raise, reraise
            $player->update(["betting"=>$action->amount]);
            $player->save();
        }

        //event
        event(new NewAction($action));

        //check if there are still more than 1 player playing
        if($action->betRound->round->tournament->players()->where("playing",true)->count()==1){
            //round stops

        }else{
            //round keeps

            //check if bet round finished

            if($bet_round->bet_phase==0){
                //this is preflop
                $check_id=$bb_id;
            }else{
                //not preflop
                $check_id=$button_id;
            }

            if($bet_round->actions()->where('user_id',$check_id)->count()>0&&$tournament->players()->where('playing',true)->distinct('betting')>1){

                //bet round finished

                //



            }else{
                //change turn and update players stack and round pot

                foreach ($players as $key => $value) {
                    $round->pot+=$value->betting;
                    $value->stack-=$value->betting;
                }
                $round->save();
                $player->save();



                //get index in the array of the current turn player
                foreach ($players as $key => $value) {
                    if($value->id==$bet_round->turn){
                        $current_turn_index=$key;
                        break;
                    }
                }

                //set the next player turn to the bet round

                $next_turn_index=$current_turn_index+1;
                if($next_turn_index>=$players->count()){
                    $next_turn_index-=$players->count();
                }
                $bet_round->turn=$players[$next_turn_index]->id;
                $bet_round->save();

            }








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

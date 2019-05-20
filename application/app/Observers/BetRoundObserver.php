<?php

namespace App\Observers;

use App\Models\BetRound;
use App\Events\NewBetRound;

class BetRoundObserver
{
    /**
     * Handle the bet round "created" event.
     *
     * @param  \App\Models\BetRound  $betRound
     * @return void
     */
    public function created(BetRound $betRound)
    {

        $tournament=$betRound->round->tournament;

    //set turn
        //get players
        $players=$tournament->players()->where('playing',true)->orderBy('sit')->get();

        //get the button player index inside de $players array
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }


        //check if its Preflop or rest of bet rounds to know who has to start playing
        $turn_index=$button_player_index;
        if($betRound->bet_phase!=0){
            $turn_index+=1;
        }else if(count($players)>2){
            $turn_index+=3;
        }

        //set as turn the id of the player
        $betRound->turn=$players[$turn_index]->id;
        $betRound->save();

        event(new NewBetRound($betRound));


    }

    /**
     * Handle the bet round "updated" event.
     *
     * @param  \App\Models\BetRound  $betRound
     * @return void
     */
    public function updated(BetRound $betRound)
    {
        //
    }

    /**
     * Handle the bet round "deleted" event.
     *
     * @param  \App\Models\BetRound  $betRound
     * @return void
     */
    public function deleted(BetRound $betRound)
    {
        //
    }

    /**
     * Handle the bet round "restored" event.
     *
     * @param  \App\Models\BetRound  $betRound
     * @return void
     */
    public function restored(BetRound $betRound)
    {
        //
    }

    /**
     * Handle the bet round "force deleted" event.
     *
     * @param  \App\Models\BetRound  $betRound
     * @return void
     */
    public function forceDeleted(BetRound $betRound)
    {
        //
    }
}

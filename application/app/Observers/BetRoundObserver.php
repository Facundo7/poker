<?php

namespace App\Observers;

use App\Models\BetRound;
use App\Models\Player;
use App\Models\Round;

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
        $round=Round::find($betRound->round_id);
        $tournament_id=$round->tournament_id;

    //set this bet round as current
        $round->current_bet_round_id=$betRound->id;
        $round->save();

    //set turn
        //get players
        $players=Player::where([['tournament_id',$tournament_id],['playing',true]])->orderBy('sit')->get();

        //get the button player index inside de $players array
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }

        //set the turn index to the button user index
        $turn_index=$button_player_index;

        //check if its Preflop or rest of bet rounds to know who has to start playing
        if($betRound->bet_phase!=0){
            $turn_index+=1;
        }else if(count($players)>2){
            $turn_index+=3;
        }

        //correct the index out of bounds
        if($turn_index>=count($players)){
            $turn_index-=count($players);
        }

        //set as turn the id of the player
        $betRound->turn=$players[$turn_index]->id;
        $betRound->save();

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

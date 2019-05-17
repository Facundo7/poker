<?php

namespace App\Observers;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\Round;

class PlayerObserver
{
    /**
     * Handle the player "created" event.
     *
     * @param  \App\Player  $player
     * @return void
     */
    public function created(Player $player)
    {
         $tournament=Tournament::find($player->tournament_id);

         //check if this player completes the table so the game starts
         if(Player::where('tournament_id', $tournament->id)->count()==$tournament->players_number){

            //set the button to the first player
            Player::where([['tournament_id',$tournament->id],['sit', 1]])->update(['button' => true]);

             //create the first round
             $round=new Round;
             $round->tournament_id=$tournament->id;
             $round->pot=0;
             $round->bb=$tournament->bb_start_value;
             $round->bb_level=$tournament->bb_level;
             $round->save();
             //set this round as current round
             $tournament->current_round_id=$round->id;
             $tournament->save();

         }
    }

    /**
     * Handle the player "updated" event.
     *
     * @param  \App\Player  $player
     * @return void
     */
    public function updated(Player $player)
    {
        //
    }

    /**
     * Handle the player "deleted" event.
     *
     * @param  \App\Player  $player
     * @return void
     */
    public function deleted(Player $player)
    {
        //
    }

    /**
     * Handle the player "restored" event.
     *
     * @param  \App\Player  $player
     * @return void
     */
    public function restored(Player $player)
    {
        //
    }

    /**
     * Handle the player "force deleted" event.
     *
     * @param  \App\Player  $player
     * @return void
     */
    public function forceDeleted(Player $player)
    {
        //
    }
}

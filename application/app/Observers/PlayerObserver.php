<?php

namespace App\Observers;

use App\Models\Player;
use App\Models\Round;
use App\Events\NewPlayer;

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

         event(new NewPlayer($player));
         $tournament=$player->tournament;

         //check if this player completes the table so the game starts
         if($tournament->players()->count()==$tournament->players_number){

            //set the button to the first player
            $tournament->players()->where('sit', 1)->update(['button' => true]);

             //create the first round
             $round=new Round;
             $round->tournament_id=$tournament->id;
             $round->pot=0;
             $round->bb=$tournament->bb_start_value;
             $round->bb_level=$tournament->bb_level;
             $round->current=true;
             $round->save();

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

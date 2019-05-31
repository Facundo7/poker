<?php

namespace App\Observers;

use App\Models\Player;
use App\Events\NewPlayer;
use App\Facades\RoundTool;

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
            $tournament->players()->where('sit',1)->update(['button'=>true]);
            RoundTool::createRound($tournament);
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

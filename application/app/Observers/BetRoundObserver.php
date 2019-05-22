<?php

namespace App\Observers;

use App\Models\BetRound;
use App\Events\NewBetRound;
use App\Facades\Game;

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

        Game::setFirstTurn($betRound);

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

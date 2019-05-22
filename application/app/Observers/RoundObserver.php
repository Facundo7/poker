<?php

namespace App\Observers;

use App\Models\Round;
use App\Models\Player;
use App\Models\BetRound;
use App\Models\DeckCard;
use App\Models\PlayerCard;
use App\Models\Tournament;
use App\Events\NewRound;

class RoundObserver
{
    /**
     * Handle the round "created" event.
     *
     * @param  \App\Round  $round
     * @return void
     */
    public function created(Round $round)
    {


        event(new NewRound($round));


    }

    /**
     * Handle the round "updated" event.
     *
     * @param  \App\Round  $round
     * @return void
     */
    public function updated(Round $round)
    {
        //
    }

    /**
     * Handle the round "deleted" event.
     *
     * @param  \App\Round  $round
     * @return void
     */
    public function deleted(Round $round)
    {
        //
    }

    /**
     * Handle the round "restored" event.
     *
     * @param  \App\Round  $round
     * @return void
     */
    public function restored(Round $round)
    {
        //
    }

    /**
     * Handle the round "force deleted" event.
     *
     * @param  \App\Round  $round
     * @return void
     */
    public function forceDeleted(Round $round)
    {
        //
    }
}

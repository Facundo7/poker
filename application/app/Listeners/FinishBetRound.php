<?php

namespace App\Listeners;

use App\Events\BetRoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishBetRound
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BetRoundFinished  $event
     * @return void
     */
    public function handle(BetRoundFinished $event)
    {
        dd($event->bet_round);
    }
}

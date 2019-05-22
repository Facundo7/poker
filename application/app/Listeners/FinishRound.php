<?php

namespace App\Listeners;

use App\Events\RoundFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishRound
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
     * @param  RoundFinished  $event
     * @return void
     */
    public function handle(RoundFinished $event)
    {
        echo $event;
    }
}

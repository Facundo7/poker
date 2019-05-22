<?php

namespace App\Observers;

use App\Models\Tournament;
use App\Models\DeckCard;
use App\Facades\Game;

class TournamentObserver
{
    /**
     * Handle the tournament "created" event.
     *
     * @param  \App\Tournament  $tournament
     * @return void
     */
    public function created(Tournament $tournament)
    {


        Game::createDeck($tournament);


    }

    /**
     * Handle the tournament "updated" event.
     *
     * @param  \App\Tournament  $tournament
     * @return void
     */
    public function updated(Tournament $tournament)
    {
        //
    }

    /**
     * Handle the tournament "deleted" event.
     *
     * @param  \App\Tournament  $tournament
     * @return void
     */
    public function deleted(Tournament $tournament)
    {
        //
    }

    /**
     * Handle the tournament "restored" event.
     *
     * @param  \App\Tournament  $tournament
     * @return void
     */
    public function restored(Tournament $tournament)
    {
        //
    }

    /**
     * Handle the tournament "force deleted" event.
     *
     * @param  \App\Tournament  $tournament
     * @return void
     */
    public function forceDeleted(Tournament $tournament)
    {
        //
    }
}

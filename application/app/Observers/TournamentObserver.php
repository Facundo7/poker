<?php

namespace App\Observers;

use App\Models\Tournament;
use Illuminate\Support\Facades\DB;

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
        $array=[];
        for($i=1;$i<53;$i++){
            $array[]=[
                'card_id'=>$i,
                'tournament_id'=>$tournament->id
            ];
        }

            DB::table('deck_cards')->insert($array);

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

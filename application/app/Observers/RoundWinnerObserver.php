<?php

namespace App\Observers;

use App\Models\RoundWinner;

class RoundWinnerObserver
{
    /**
     * Handle the round winner "created" event.
     *
     * @param  \App\Models\RoundWinner  $roundWinner
     * @return void
     */
    public function created(RoundWinner $roundWinner)
    {
        $roundWinner->player->stack+=$roundWinner->amount;
        $roundWinner->player->save();
    }

    /**
     * Handle the round winner "updated" event.
     *
     * @param  \App\Models\RoundWinner  $roundWinner
     * @return void
     */
    public function updated(RoundWinner $roundWinner)
    {
        //
    }

    /**
     * Handle the round winner "deleted" event.
     *
     * @param  \App\Models\RoundWinner  $roundWinner
     * @return void
     */
    public function deleted(RoundWinner $roundWinner)
    {
        //
    }

    /**
     * Handle the round winner "restored" event.
     *
     * @param  \App\Models\RoundWinner  $roundWinner
     * @return void
     */
    public function restored(RoundWinner $roundWinner)
    {
        //
    }

    /**
     * Handle the round winner "force deleted" event.
     *
     * @param  \App\Models\RoundWinner  $roundWinner
     * @return void
     */
    public function forceDeleted(RoundWinner $roundWinner)
    {
        //
    }
}

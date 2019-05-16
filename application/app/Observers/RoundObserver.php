<?php

namespace App\Observers;

use App\Models\Round;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use Illuminate\Support\Facades\Log;

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

         $cards=DB::table('deck_cards')->where('tournament_id',$round->tournament_id)->select('card_id')->get()->toArray();
         $players=Player::where('tournament_id', $round->tournament_id)->get();
         $remove=[];
         for($i=0;$i<count($players);$i++){
            for($j=1;$j<3;$j++){
                $index=rand(0,count($cards)-1);
                DB::table('player_cards')->insert(
                    [
                        'round_id'=>$round->id,
                        'player_id'=>$players[$i]->id,
                        'card_id'=>$cards[$index]->card_id,
                        'position'=>$j,
                    ]
                );
                $remove[]=$cards[$index]->card_id;
                array_splice($cards, $index, 1);
            }
        }
        DB::table('deck_cards')
            ->where('tournament_id', $round->tournament_id)
            ->whereIn('card_id',$remove)
            ->update(['available' => false]);
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

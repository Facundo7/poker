<?php

namespace App\Observers;

use App\Models\Round;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use Illuminate\Support\Facades\Log;
use App\Models\BetRound;

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
         //set all deck cards available
         DB::table('deck_cards')->where('tournament_id', $round->tournament_id)->update(['available' => true]);
         //create an array with all cards id (1 to 52)
         $cards=[];
         for($i=1;$i<=52;$i++){
            $cards[]=$i;
         }
         //get players of the round
         $players=Player::where([['tournament_id', $round->tournament_id],['alive', true]])->get();

         $remove=[]; //this array will contain the id of the cards that have been dealt so they can not be available again until next round
         //for each player deal 2 cards and store it in "player_cards" table
         for($i=0;$i<count($players);$i++){
            for($j=1;$j<3;$j++){
                $index=rand(0,count($cards)-1);
                DB::table('player_cards')->insert(
                    [
                        'round_id'=>$round->id,
                        'player_id'=>$players[$i]->id,
                        'card_id'=>$cards[$index],
                        'position'=>$j,
                    ]
                );
                $remove[]=$cards[$index];
                array_splice($cards, $index, 1);
            }
        }
        //set as unavailable those cards that have been dealt.
        DB::table('deck_cards')
            ->where('tournament_id', $round->tournament_id)
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);

        //set as playing all the players
        Player::where('tournament_id',$round->tournament_id)->update(['playing'=>true]);

        //create the first bet round
        BetRound::create([
            'round_id' => $round->id,
            'bet_phase' => 0,
        ]);
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

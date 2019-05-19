<?php

namespace App\Observers;

use App\Models\Round;
use App\Models\Player;
use App\Models\BetRound;
use App\Models\DeckCard;
use App\Models\PlayerCard;
use App\Models\Tournament;

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

        $tournament=Tournament::find($round->tournament_id);

         //set all deck cards available
         $tournament->deckCards()->update(['available' => true]);

         //create an array with all cards id (1 to 52)
         $cards=[];
         for($i=1;$i<=52;$i++){
            $cards[]=$i;
         }
         //get players of the round
         $players=$tournament->players()->where('alive', true)->get();

         $remove=[]; //this array will contain the id of the cards that have been dealt so they can not be available again until next round
         //for each player deal 2 cards and store it in "player_cards" table
         for($i=0;$i<count($players);$i++){
            for($j=1;$j<3;$j++){
                $index=rand(0,count($cards)-1);
                PlayerCard::insert(
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
        $tournament->deckCards()
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);

        //set as playing all the players
        $tournament->players()->update(['playing'=>true]);

        //create the first bet round
        $bet_round=new BetRound;
            $bet_round->round_id=$round->id;
            $bet_round->bet_phase=0;
            $bet_round->current=true;
            $bet_round->save();
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

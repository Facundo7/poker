<?php

namespace App\Tools;

use App\Models\Tournament;
use App\Models\Round;
use App\Models\PlayerCard;
use App\Models\BetRound;
use App\Models\BoardCard;

class RoundTool
{

    public function createRound(Tournament $tournament)
    {
        //set deck cards available
        $tournament->deckCards()->update(['available' => true]);
        $tournament->players()->update(['total_bet' => 0]);

        //create round
        $round=new Round;
        $round->tournament_id=$tournament->id;
        $round->pot=0;
        $round->bb=$tournament->bb;
        $round->bb_level=$tournament->bb_level;
        $round->current=true;
        $round->save();
    }

    public function dealPlayerCards(Round $round)
    {
        //create an array with all cards id (1 to 52)
        $cards=[];
         for($i=1;$i<=52;$i++){
            $cards[]=$i;
         }

         //get players of the round
         $players=$round->tournament->alivePlayers()->orderBy('sit')->get();

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
        //set dealt cards as unavailable
        $round->tournament->deckCards()
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);

    }


    public function dealBoardCards(BetRound $bet_round){

        $tournament=$bet_round->round->tournament;

        switch ($bet_round->bet_phase) {
            case 1:
                //Flop
                $i=1;
                $max=3;
                break;
            case 2:
                //Turn
                $i=4;
                $max=4;
                break;
            case 3:
                //River
                $i=5;
                $max=5;
                break;
            default:
                $i=1;
                $max=0;
                break;
        }


        //create an array with available cards)
        $cards=$tournament->availableDeckCards()->pluck('card_id')->toArray();


         $remove=[]; //this array will contain the id of the cards that have been dealt so they can not be available again until next round

         //

         for($i;$i<=$max;$i++){

                $index=rand(0,count($cards)-1);
                $board_card=new BoardCard;

                $board_card->round_id=$bet_round->round->id;
                $board_card->card_id=$cards[$index];
                $board_card->position=$i;
                $board_card->save();


                $remove[]=$cards[$index];
                array_splice($cards, $index, 1);
            }

        //set dealt cards as unavailable
        $tournament->deckCards()
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);
    }
}

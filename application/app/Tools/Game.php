<?php

namespace App\Tools;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\DeckCard;
use App\Models\BetRound;
use App\Models\Round;
use App\Models\Action;

class Game
{

    public function createDeck(Tournament $tournament)
    {
        //generate deck cards for this tournament
        $array=[];
        for($i=1;$i<=52;$i++){
            $array[]=[
                'card_id'=>$i,
                'tournament_id'=>$tournament->id
            ];
        }
        DeckCard::insert($array);

    }

    public function createRound(Tournament $tournament)
    {

        //create the first round
        $round=new Round;
        $round->tournament_id=$tournament->id;
        $round->pot=0;
        $round->bb=$tournament->bb;
        $round->bb_level=$tournament->bb_level;
        $round->current=true;
        $round->save();

        //set deck cards available
        $tournament->deckCards()->update(['available' => true]);


    }


    public function dealPlayerCards(Tournament $tournament)
    {
        //create an array with all cards id (1 to 52)
        $cards=[];
         for($i=1;$i<=52;$i++){
            $cards[]=$i;
         }

         //get players of the round
         $players=$tournament->alivePlayers()->orderBy('sit')->get();

         $remove=[]; //this array will contain the id of the cards that have been dealt so they can not be available again until next round

         //for each player deal 2 cards and store it in "player_cards" table
         for($i=0;$i<count($players);$i++){
            for($j=1;$j<3;$j++){
                $index=rand(0,count($cards)-1);
                PlayerCard::insert(
                    [
                        'round_id'=>$tournament->currentRound->id,
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
        $tournament->deckCards()
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);

    }


    public function setBlinds(Tournament $tournament){

        $players=$tournament->alivePlayers()->orderBy('sit')->get();

        //get index of the button player
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }

        //set bb, sb
        //check if this is heads up (2 players)
        if($players->count()==2){

            $bb_index=$button_player_index+1;
            $sb_index=$button_player_index;

        }else{

            $bb_index=$button_player_index+2;
            $sb_index=$button_player_index+1;

        }

        //correct the sb and bb index if they are out of the bounds
        if($bb_index>=count($players)){
            $bb_index-=count($players);
        }

        if($sb_index>=count($players)){
            $sb_index-=count($players);
        }

        $tournament->alivePlayers()->update(['playing'=>true, 'sb'=>false, 'bb'=>false]);

        //set button bb and sb
        $players[$sb_index]->sb=true;
        $players[$sb_index]->save();
        $players[$bb_index]->bb=true;
        $players[$bb_index]->save();
        $players[$sb_index]->save();


    }


    public function setFirstTurn(BetRound $betRound){

        //set turn
        //get players
        $players=$betRound->round->tournament->playingPlayers()->orderBy('sit')->get();

        //get the button player index inside de $players array
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }


        //check if its Preflop or rest of bet rounds to know who has to start playing
        $turn_index=$button_player_index;
        if($betRound->bet_phase!=0){
            $turn_index+=1;
        }else if(count($players)>2){
            $turn_index+=3;
        }

        //set as turn the id of the player
        $betRound->turn=$players[$turn_index]->id;
        $betRound->save();


    }

    public function createBetRound(Round $round, $bet_phase){

        $bet_round=new BetRound;
        $bet_round->round_id=$round->id;
        $bet_round->bet_phase=$bet_phase;
        $bet_round->current=true;
        $bet_round->save();

        return $bet_round;

    }

    public function updatePlayer(Action $action)
    {
        //update player
        $player=$action->player;
        if($action->action==4){

            $player->update(["playing"=>false]);
            $player->save();

            if($action->betRound->round->tournament->playingPlayers()->count()==1){
                //round stops
                //1 player left event
            }else{

            }

        }else if($action->action!=0){
            //call, raise, reraise
            $player->update(["betting"=>$action->amount]);
            $player->save();

                //
        }

    }

    public function changeButton(Tournament $tournament){

        $players=$tournament->alivePlayers()->orderBy('sit')->get();

        //get index of the button player
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }

        //deactivate button for current button player
        $players[$button_player_index]->button=false;

        //set button for next player
        $button_player_index+=1;
        if($button_player_index>=count($players)){
            $button_player_index-=count($players);
        }

        $players[$button_player_index]->button=true;
        $players[$button_player_index]->save();

    }

    public function nextTurn(Tournament $tournament)
    {
        $players=$tournament->playingPlayers()->orderBy('sit')->get();
        $bet_round=$tournament->currentRound->currentBetRound;

        //get index in the array of the current turn player
        foreach ($players as $key => $value) {
            if($value->id==$bet_round->turn){
                $current_turn_index=$key;
                break;
            }
        }

        //set the next player turn to the bet round

        $next_turn_index=$current_turn_index+1;
        if($next_turn_index>=$players->count()){
            $next_turn_index-=$players->count();
        }
        $bet_round->turn=$players[$next_turn_index]->id;
        $bet_round->save();
    }

    public function updatePotStack(Tournament $tournament)
    {
        $players=$tournament->alivePlayers;
        $round=$tournament->currentRound;

        foreach ($players as $key => $player) {
            $round->pot+=$player->betting;
            $player->stack-=$player->betting;
            $player->betting=0;
        }

        $round->save();
        $players->save();
    }

}

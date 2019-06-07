<?php

namespace App\Tools;

use App\Models\Round;
use App\Models\BetRound;
use App\Models\Tournament;

class BetRoundTool
{

    public function createBetRound(Round $round, $bet_phase){

        $bet_round=new BetRound;
        $bet_round->round_id=$round->id;
        $bet_round->bet_phase=$bet_phase;
        $bet_round->current=true;
        $bet_round->players=$round->tournament->playingPlayers()->count();
        $bet_round->save();

        return $bet_round;

    }

    public function setFirstTurn(BetRound $betRound){

        //set turn
        //get players
        $players=$betRound->round->tournament->alivePlayers()->orderBy('sit')->get();

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

        if($turn_index>=count($players)){
            $turn_index-=count($players);
        }

        for($i=0;$i<count($players);$i++){
            if($players[$turn_index]->playing){
                //set as turn the id of the player
                $betRound->turn=$players[$turn_index]->id;
                $betRound->save();
                break;
            }else {

             $turn_index++;
                if($turn_index>=count($players)){
                    $turn_index-=count($players);
                }
            }
        }
    }

    public function nextTurn(Tournament $tournament){

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
        if($next_turn_index>=count($players)){
            $next_turn_index-=count($players);
        }
        $bet_round->turn=$players[$next_turn_index]->id;
        $bet_round->save();
    }
}

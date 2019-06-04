<?php

namespace App\Tools;

use App\Models\Tournament;
use App\Models\DeckCard;
use App\Models\BetRound;
use App\Models\Round;
use App\Models\Action;
use App\Models\PlayerCard;
use App\Models\BoardCard;

class Game
{

    public function createDeck(Tournament $tournament){

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

        //set bb and sb
        $players[$sb_index]->sb=true;
        $players[$sb_index]->betting=$tournament->currentRound->bb/2;

        $players[$bb_index]->bb=true;
        $players[$bb_index]->betting=$tournament->currentRound->bb;

        $players[$bb_index]->save();
        $players[$sb_index]->save();
    }

    public function killPlayers(Tournament $tournament){

        $tournament->alivePlayers()->where('stack',0)->update(['alive'=>false, 'playing'=>false, 'sb'=>false, 'bb'=>false]);

    }

    public function finishTournament(Tournament $tournament){

        $tournament->active=false;
        $tournament->winner_id=$tournament->alivePlayers->first()->id;
        $tournament->save();

    }

    public function updatePlayer(Action $action){
        //update player
        $player=$action->player;
        if($action->action==4){

            $player->playing=false;
            $player->save();

        }else if($action->action!=0){
            //call, raise, reraise
            $player->betting+=$action->amount;
            $player->save();
        }
    }

    public function changeButton(Tournament $tournament){

        $players=$tournament->players()->orderBy('sit')->get();

        //get index of the button player
        for($i=0;$i<count($players);$i++){
            if($players[$i]->button){
                $button_player_index=$i;
                break;
            }
        }

        //deactivate button for current button player
        $players[$button_player_index]->button=false;
        $players[$button_player_index]->save();

        do{

        //set button for next player
        $button_player_index+=1;
        if($button_player_index>=count($players)){
            $button_player_index-=count($players);
        }

        if($players[$button_player_index]->alive){
            $players[$button_player_index]->button=true;
            $players[$button_player_index]->save();
            break;
        }

        }while(true);

    }


    public function updatePotAndStack(Tournament $tournament){
        $players=$tournament->alivePlayers;
        $round=$tournament->currentRound;

        foreach ($players as $index => $player) {
            $round->pot+=$player->betting;
            $player->stack-=$player->betting;
            $player->total_bet+=$player->betting;
            $player->betting=0;
            $player->save();
        }
        $round->save();
    }


}


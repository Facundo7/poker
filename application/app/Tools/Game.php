<?php

namespace App\Tools;

use App\Models\Tournament;
use App\Models\DeckCard;
use App\Models\BetRound;
use App\Models\Round;
use App\Models\Action;
use App\Models\PlayerCard;
use App\Models\BoardCard;
use App\Models\RoundWinner;

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
        $bet_round->players=$round->tournament->playingPlayers()->count();
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

        }else if($action->action!=0){
            //call, raise, reraise
            $player->update(["betting"=>$action->amount]);
            $player->save();

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
                //is preflop
                break;
        }


        //create an array with available cards)
        $cards=$tournament->availableDeckCards()->select('id')->get()->toArray();


         $remove=[]; //this array will contain the id of the cards that have been dealt so they can not be available again until next round

         //
         for($i;$i<=$max;$i++){

                $index=rand(0,count($cards)-1);
                BoardCard::insert(
                    [
                        'round_id'=>$bet_round->round->id,
                        'card_id'=>$cards[$index],
                        'position'=>$i,
                    ]
                );
                $remove[]=$cards[$index];
                array_splice($cards, $index, 1);
            }

        //set dealt cards as unavailable
        $tournament->deckCards()
            ->whereIn('card_id', $remove)
            ->update(['available' => false]);

    }

    public function evaluateCards($players, $board_cards, $round){

        $hands=[];
        $evaluated_hands=[];

        //store cards in hands
        for($i=0;$i<$players->length;$i++)
        {
            $hands[]=array(players[$i]->cards[0]->card,
            $players[$i]->cards[1]->card,
            $board_cards[0]->card,
            $board_cards[1]->card,
            $board_cards[2]->card,
            $board_cards[3]->card,
            $board_cards[4]->card
            );
        }

        //evaluate every hand and fill evaluated hand array
        for($i=0;$i<$hands->length;$i++)
        {
            $hand=$hands[$i];

            //order by value
            for($j=0;$j<=6;$j++){

                $aux=$hand[$j];
                $k=$j-1;
                while ($k >= 0 && $hand[$k]->value>$aux->value) {

                    $hand[$k+1]=$hand[$k];
                    $k--;

                }
                $hand[$k+1]=$aux;
            }



            $evaluated_hands[$i]=checkStraightFlush($hand);

            if($evaluated_hands[$i]==false){

                $evaluated_hands[$i]=checkPoker($hand);

                if($evaluated_hands[$i]==false){

                    $evaluated_hands[$i]=checkFullHouse($hand);

                    if($evaluated_hands[$i]==false){

                        $evaluated_hands[$i]=checkFlush($hand);

                        if($evaluated_hands[$i]==false){

                            $evaluated_hands[$i]=checkStraight($hand);

                            if($evaluated_hands[$i]==false){

                                $evaluated_hands[$i]=checkThree($hand);

                                if($evaluated_hands[$i]==false){

                                    $evaluated_hands[$i]=checkTwoPair($hand);

                                    if($evaluated_hands[$i]==false){

                                        $evaluated_hands[$i]=checkPair($hand);

                                        if($evaluated_hands[$i]==false){

                                            $evaluated_hands[$i]=array(
                                                1,
                                                $hand[6]->value,
                                                $hand[5]->value,
                                                $hand[4]->value,
                                                $hand[3]->value,
                                                $hand[2]->value,
                                            );

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $evaluated_hands[$i][6]=$players[$i]->id;

            }

            $winners=getWinners($evaluated_hands);

            for ($i=0; $i < $winners->length; $i++) {
                $winner= new RoundWinner;
                $winner->player_id=$winners[$i][6];
                $winner->round_id=$round->id;
                $winner->amount=$round->pot;
                $winner->save();
            }


            }
            public function checkStraightFlush($hand){



                for ($i=0; $i <= 6; $i++) {
                    switch ($hand[$i]->value) {
                        case 1:
                            $spades[]=$hand[$i];
                            break;
                        case 2:
                            $hearts[]=$hand[$i];
                            break;
                        case 3:
                            $clubs[]=$hand[$i];
                            break;
                        case 4:
                            $diamonds[]=$hand[$i];
                            break;
                    }
                }


                if($clubs->length>=5)
                {
                    $hand=$clubs;
                } else if($spades->length>=5)
                {
                    $hand=$spades;
                } if($hearts->length>=5)
                {
                    $hand=$hearts;
                } if($diamonds->length>=5)
                {
                    $hand=$diamonds;
                } else {
                    return false;
                }

                //check straight in the hand

                if($hand[$hand->length-1]->value==14&&
                $hand[0]->value==2&&
                $hand[1]->value==3&&
                $hand[2]->value==4&&
                $hand[3]->value==5
                ){
                    return array(
                        9,
                        5,
                        0,
                        0,
                        0,
                        0
                    );
                }else{

                    for ($i=$hand->length-1; $i > 3 ; $i--) {

                        if($hand[$i]->value==$hand[$i-1]->value+1&&
                        $hand[$i]->value==$hand[$i-2]->value+2&&
                        $hand[$i]->value==$hand[$i-3]->value+3&&
                        $hand[$i]->value==$hand[$i-4]->value+4
                        ){
                            return array(
                                9,
                                $hand[$i]->value,
                                0,
                                0,
                                0,
                                0
                            );
                        }

                    }

                    return false;

                }


            }

            public function checkPoker($hand){

                for ($i=$hand->length-1; $i > 2 ; $i--) {

                    if($hand[$i]->value==$hand[$i-1]->value&&
                    $hand[$i]->value==$hand[$i-2]->value&&
                    $hand[$i]->value==$hand[$i-3]->value
                    ){

                        $hand2=$hand;
                        array_splice($hand2,$i-3,4);
                        return array(
                            8,
                            $hand[$i]->value,
                            $hand2[$hand2->length-1]->value,
                            0,
                            0,
                            0
                        );
                    }

                }
                return false;


            }

            public function checkFullHouse($hand){

                for ($i=$hand->length-1; $i > 1 ; $i--) {

                    if($hand[$i]->value==$hand[$i-1]->value&&
                    $hand[$i]->value==$hand[$i-2]->value
                    ){

                        // for ($j=0; $j < $hand->length; $j++) {

                        //     if (!in_array($j, array($i,$i-1,$i-2))) {
                        //         $hand2[]=$hand[$j];
                        //     }
                        // }

                        $hand2=$hand;
                        array_splice($hand2,$i-2,3);

                         //check couple in hand2
                         for ($k=$hand2->length-1; $k > 0 ; $k--) {

                            if($hand2[$k]->value==$hand2[$k-1]->value){
                                return array(
                                    7,
                                    $hand[$i]->value,
                                    $hand2[$k]->value,
                                    0,
                                    0,
                                    0
                                );
                            }

                        }
                        return false;

                    }

                }
                return false;

            }

            public function checkFlush($hand){

                for ($i=0; $i <= 6; $i++) {
                    switch ($hand[$i]->value) {
                        case 1:
                            $spades[]=$hand[$i];
                            break;
                        case 2:
                            $hearts[]=$hand[$i];
                            break;
                        case 3:
                            $clubs[]=$hand[$i];
                            break;
                        case 4:
                            $diamonds[]=$hand[$i];
                            break;
                    }
                }


                if($clubs->length>=5)
                {
                    $hand=$clubs;
                } else if($spades->length>=5)
                {
                    $hand=$spades;
                } if($hearts->length>=5)
                {
                    $hand=$hearts;
                } if($diamonds->length>=5)
                {
                    $hand=$diamonds;
                } else {
                    return false;
                }

                return array(
                    6,
                    $hand[$hand->length-1]->value,
                    0,
                    0,
                    0,
                    0
                );

            }

            public function checkStraight($hand){}

            public function checkThree($hand){

                for ($i=$hand->length-1; $i > 1 ; $i--) {

                    if($hand[$i]->value==$hand[$i-1]->value&&
                    $hand[$i]->value==$hand[$i-2]->value
                    ){
                        $hand2=$hand;
                        array_splice($hand2,$i-2,3);
                        return array(
                            4,
                            $hand[$i]->value,
                            $hand2[$hand2->length-1]->value,
                            $hand2[$hand2->length-2]->value,
                            0,
                            0
                        );
                    }

                }
                return false;


            }

            public function checkTwoPair($hand){

                for ($i=$hand->length-1; $i > 0 ; $i--) {

                    if($hand[$i]->value==$hand[$i-1]->value
                    ){
                        $hand2=$hand;
                        array_splice($hand2,$i-1,2);

                        for ($j=$hand2->length-1; $j > 0 ; $j--) {

                            if($hand2[$j]->value==$hand2[$j-1]->value
                            ){
                                $hand3=$hand2;
                                array_splice($hand3,$i-1,2);
                                return array(
                                    3,
                                    $hand[$i]->value,
                                    $hand2[$j]->value,
                                    $hand3[$hand2->length-1],
                                    0,
                                    0
                                );
                            }

                        }
                        return false;

                    }

                }
                return false;


            }

            public function checkPair($hand){

                for ($i=$hand->length-1; $i > 0 ; $i--) {

                    if($hand[$i]->value==$hand[$i-1]->value
                    ){
                        $hand2=$hand;
                        array_splice($hand2,$i-1,2);
                        return array(
                            3,
                            $hand[$i]->value,
                            $hand2[$hand2->length-1]->value,
                            $hand2[$hand2->length-2]->value,
                            $hand2[$hand2->length-3]->value,
                            0
                        );
                    }
                }
                return false;

            }


            public function getWinners($evaluations){


                for($i=0;$i<6;$i++)
                {

                    $values=[];

                    for($j=0;$j<$evaluations->length;$j++)
                    {
                        $values[]=$evaluations[$j][$i];
                    }

                    $winners=[];

                    for($j=0;$j<$evaluations->length;$j++)
                    {
                        if($evaluations[$j][$i]==max($values))
                        {
                            $winners[]=$evaluations[$j];
                        }
                    }

                    if($winners->length==1)
                    {
                        break;
                    }

                }

                return $winners;

            }


        }


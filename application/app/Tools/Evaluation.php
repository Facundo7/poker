<?php

namespace App\Tools;

use App\Models\Player;

class Evaluation
{

    public function evaluateCards($tournament, $players){

        $board_cards=$tournament->currentRound->boardCards;
        $hands=[];
        $evaluated_hands=[];

        //store cards in hands
        for($i=0;$i<count($players);$i++)
        {
            $hands[]=array($players[$i]->cards[0]->card,
            $players[$i]->cards[1]->card,
            $board_cards[0]->card,
            $board_cards[1]->card,
            $board_cards[2]->card,
            $board_cards[3]->card,
            $board_cards[4]->card
            );
        }

        //evaluate every hand and fill evaluated hand array
        for($i=0;$i<count($hands);$i++)
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



            $evaluated_hands[$i]=$this->checkStraightFlush($hand);

            if($evaluated_hands[$i]==false){

                $evaluated_hands[$i]=$this->checkPoker($hand);

                if($evaluated_hands[$i]==false){

                    $evaluated_hands[$i]=$this->checkFullHouse($hand);

                    if($evaluated_hands[$i]==false){

                        $evaluated_hands[$i]=$this->checkFlush($hand);

                        if($evaluated_hands[$i]==false){

                            $evaluated_hands[$i]=$this->checkStraight($hand);

                            if($evaluated_hands[$i]==false){

                                $evaluated_hands[$i]=$this->checkThree($hand);

                                if($evaluated_hands[$i]==false){

                                    $evaluated_hands[$i]=$this->checkTwoPair($hand);

                                    if($evaluated_hands[$i]==false){

                                        $evaluated_hands[$i]=$this->checkPair($hand);

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

            $all_players=$tournament->alivePlayers()->orderBy('total_bet')->get();



            do {
                $side_pot=0;
                $smallest_bet=$all_players[0]->total_bet;
                foreach ($all_players as $index => $player) {
                    $side_pot+=$smallest_bet;
                    $player->total_bet-=$smallest_bet;
                    $player->save();
                    if($player->total_bet<1){
                        $all_players->forget($index);
                    }
                }
                $all_players=$all_players->values();


                $winners=$this->getWinners($evaluated_hands);


                foreach ($winners as $index => $winner) {
                    $player=Player::find($winner[6]);
                    $player->stack+=round($side_pot/count($winners));
                    $player->save();
                }


                foreach ($evaluated_hands as $index => $hand) {
                    if(Player::find($hand[6])->total_bet==0){
                        unset($evaluated_hands[$index]);
                    }
                }

                $evaluated_hands=array_values($evaluated_hands);

            } while (count($all_players) > 1);


            // for ($i=0; $i < count($winners); $i++) {
            //     $winner= new RoundWinner;
            //     $winner->player_id=$winners[$i][6];
            //     $winner->round_id=$round->id;
            //     $winner->amount=$round->pot/count($winners);
            //     $winner->save();
            // }


            }
    public function checkStraightFlush($hand){


        $spades=[];
        $clubs=[];
        $hearts=[];
        $diamonds=[];

        for ($i=0; $i <= 6; $i++) {
            switch ($hand[$i]->suit) {
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


        if(count($clubs)>=5)
        {
            $hand=$clubs;
        } else if(count($spades)>=5)
        {
            $hand=$spades;
        }else if(count($hearts)>=5)
        {
            $hand=$hearts;
        }else if(count($diamonds)>=5)
        {
            $hand=$diamonds;
        }else {
            return false;
        }


        //check straight in the hand

        if($hand[count($hand)-1]->value==14&&
            $hand[0]->value==2&&
            $hand[1]->value==3&&
            $hand[2]->value==4&&
            $hand[3]->value==5)
        {
            return array(
                9,
                5,
                0,
                0,
                0,
                0
            );

        }else{

            for ($i=count($hand)-1; $i > 3 ; $i--){

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

        for ($i=count($hand)-1; $i > 2 ; $i--) {

            if($hand[$i]->value==$hand[$i-1]->value&&
                $hand[$i]->value==$hand[$i-2]->value&&
                $hand[$i]->value==$hand[$i-3]->value
            ){
                $hand2=$hand;
                array_splice($hand2,$i-3,4);
                return array(
                    8,
                    $hand[$i]->value,
                    $hand2[count($hand2)-1]->value,
                    0,
                    0,
                    0
                );
            }

        }
        return false;
    }

    public function checkFullHouse($hand){

        for ($i=count($hand)-1; $i > 1 ; $i--) {

            if($hand[$i]->value==$hand[$i-1]->value&&
                $hand[$i]->value==$hand[$i-2]->value
            ){

                $hand2=$hand;
                array_splice($hand2,$i-2,3);

                //check couple in hand2
                for ($k=count($hand2)-1; $k > 0 ; $k--) {

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

        $spades=[];
        $clubs=[];
        $hearts=[];
        $diamonds=[];

        for ($i=0; $i <= 6; $i++) {
            switch ($hand[$i]->suit) {
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

        if(count($clubs)>=5)
        {
            $hand=$clubs;
        } else if(count($spades)>=5)
        {
            $hand=$spades;
        } else if(count($hearts)>=5)
        {
            $hand=$hearts;
        } else if(count($diamonds)>=5)
        {
            $hand=$diamonds;
        } else {
            return false;
        }

        return array(
            6,
            $hand[count($hand)-1]->value,
            0,
            0,
            0,
            0
        );

    }

    public function checkStraight($hand){

        if($hand[count($hand)-1]->value==14&&$hand[0]->value==2){

            if (in_array(3, array($hand[1]->value,
                                    $hand[2]->value,
                                    $hand[3]->value,
                                    $hand[4]->value,
                                    $hand[5]->value))&&
                in_array(4, array($hand[1]->value,
                                    $hand[2]->value,
                                    $hand[3]->value,
                                    $hand[4]->value,
                                    $hand[5]->value))&&
                in_array(5, array($hand[1]->value,
                                    $hand[2]->value,
                                    $hand[3]->value,
                                    $hand[4]->value,
                                    $hand[5]->value))
            ){

                return array(
                    5,
                    5,
                    0,
                    0,
                    0,
                    0
                );
            }
        }



        for ($i=count($hand)-1; $i > 3 ; $i--) {

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



    public function checkThree($hand){

        for ($i=count($hand)-1; $i > 1 ; $i--) {

            if($hand[$i]->value==$hand[$i-1]->value&&
                $hand[$i]->value==$hand[$i-2]->value
            ){
                $hand2=$hand;
                array_splice($hand2,$i-2,3);
                return array(
                    4,
                    $hand[$i]->value,
                    $hand2[count($hand2)-1]->value,
                    $hand2[count($hand2)-2]->value,
                    0,
                    0
                );
            }
        }
        return false;
    }

    public function checkTwoPair($hand){

        for ($i=count($hand)-1; $i > 0 ; $i--) {

            if($hand[$i]->value==$hand[$i-1]->value){

                $hand2=$hand;
                array_splice($hand2,$i-1,2);

                for ($j=count($hand2)-1; $j > 0 ; $j--) {

                    if($hand2[$j]->value==$hand2[$j-1]->value){

                        $hand3=$hand2;
                        array_splice($hand3,$j-1,2);
                        return array(
                            3,
                            $hand[$i]->value,
                            $hand2[$j]->value,
                            $hand3[count($hand3)-1]->value,
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

        for ($i=count($hand)-1; $i > 0 ; $i--) {

            if($hand[$i]->value==$hand[$i-1]->value){

                $hand2=$hand;
                array_splice($hand2,$i-1,2);
                return array(
                    2,
                    $hand[$i]->value,
                    $hand2[count($hand2)-1]->value,
                    $hand2[count($hand2)-2]->value,
                    $hand2[count($hand2)-3]->value,
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

            for($j=0;$j<count($evaluations);$j++)
            {
                $values[]=$evaluations[$j][$i];
            }

            $x=count($evaluations);

            for($j=0;$j<$x;$j++)
            {
                if($evaluations[$j][$i]!=max($values))
                {
                    unset($evaluations[$j]);
                }
            }

            $evaluations=array_values($evaluations);

            if(count($evaluations)==1)
            {
                break;
            }

        }
        return $evaluations;
    }
}


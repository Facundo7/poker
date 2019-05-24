<?php

namespace App\Repositories;

use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;

class PlayerRepository implements PlayerRepositoryInterface
{

    /**
     * Store a newly created Client in storage.
     *
     * @param array
     * @return player
     **/
    public function save($tournament_id)
    {
        $tournament=Tournament::find($tournament_id);
        $number_of_players=$tournament->players->count();
        if(($tournament->playerLogged=='')&&($tournament->players_number>$number_of_players)){
        $player = new Player;
        $player->tournament_id=$tournament->id;
        $player->sit=$number_of_players+1;
        $player->stack=$tournament->initial_stack;
        $player->user_id=Auth::id();
        $player->save();
        return $player;
        }else return 'user already registered';
    }

    /**
     * Get's a player by it's ID
     *
     * @param int
     * @return collection
     */
    public function find($id)
    {
        return Player::find($id);
    }

    /**
     * Get's all players.
     *
     * @return mixed
     */
    public function all()
    {
        return Player::all();
    }

    /**
     * Deletes a player.
     *
     * @param int
     */
    public function delete($id)
    {
        Player::destroy($id);
    }

    /**
     * Updates a player.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data)
    {
        Player::find($id)->update($data);
    }
}



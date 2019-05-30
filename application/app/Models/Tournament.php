<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tournament extends Model
{
    protected $fillable = [
        'title', 'bb', 'bb_start_value', 'bb_increase_time', 'bb_increase_value', 'initial_stack', 'turn_seconds', 'buy_in', 'players_number'
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function currentRound()
    {
        return $this->hasOne(Round::class)->where('current',true)->with('boardCards');
    }

    public function players()
    {
        return $this->hasMany(Player::class)->with('user');
    }
    public function button()
    {
        return $this->hasOne(Player::class)->where('button',true)->with('user');
    }
    public function bb()
    {
        return $this->hasOne(Player::class)->where('button',true)->with('user');
    }
    public function sb()
    {
        return $this->hasOne(Player::class)->where('button',true)->with('user');
    }
    public function turnPlayer($bet_round)
    {
        return $this->hasOne(Player::class)->where('id',$bet_round->turn)->with('user');
    }
    public function playerLogged()
    {
        return $this->hasOne(Player::class)->where('user_id',Auth::id());
    }
    public function alivePlayers()
    {
        return $this->hasMany(Player::class)->where('alive',true);
    }
    public function playingPlayers()
    {
        return $this->hasMany(Player::class)->where('playing',true);
    }
    public function deckCards()
    {
        return $this->hasMany(DeckCard::class);
    }
    public function availableDeckCards()
    {
        return $this->hasMany(DeckCard::class)->where('available',true);
    }
    public function winner(){
        return $this->belongsTo(User::class, 'winner_id');
    }
}

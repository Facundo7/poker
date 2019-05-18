<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'title', 'bb', 'bb_start_value', 'bb_increase_time', 'bb_increase_value', 'initial_stack', 'turn_seconds', 'buy_in', 'players_number'
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function deckCards()
    {
        return $this->hasMany(DeckCard::class);
    }
}

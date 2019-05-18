<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function betRounds()
    {
        return $this->hasMany(BetRound::class);
    }

    public function roundWinners()
    {
        return $this->hasMany(RoundWinner::class);
    }

    public function playerCards()
    {
        return $this->hasMany(PlayerCard::class);
    }

    public function boardCards()
    {
        return $this->hasMany(BoardCard::class);
    }
}

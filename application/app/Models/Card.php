<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $timestamps = false;

    public function deckCards()
    {
        return $this->hasMany(DeckCard::class);
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

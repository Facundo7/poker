<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundWinner extends Model
{
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
    public function betRound()
    {
        return $this->belongsTo(BetRound::class);
    }
}

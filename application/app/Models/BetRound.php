<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetRound extends Model
{

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardCard extends Model
{
    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}

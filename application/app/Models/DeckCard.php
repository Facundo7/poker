<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeckCard extends Model
{
    public $timestamps = false;

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}

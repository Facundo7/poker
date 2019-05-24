<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stack', 'sit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function roundWinners()
    {
        return $this->hasMany(RoundWinner::class);
    }

    public function cards()
    {
        return $this->hasMany(PlayerCard::class)->with('card');
    }

}

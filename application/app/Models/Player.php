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

}

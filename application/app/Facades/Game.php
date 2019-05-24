<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class Game extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Game';
    }
}

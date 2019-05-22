<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Tools\Game;
class ToolServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Game', function () {
            return new Game;
        });
    }
}

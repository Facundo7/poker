<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Tools\Game;
use App\Tools\Evaluation;
class ToolServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Game', function () {
            return new Game;
        });
        $this->app->bind('Evaluation', function () {
            return new Evaluation;
        });
    }
}

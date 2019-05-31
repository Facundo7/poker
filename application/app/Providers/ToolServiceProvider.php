<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Tools\Game;
use App\Tools\Evaluation;
use App\Tools\BetRoundTool;
use App\Tools\RoundTool;

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
        $this->app->bind('BetRoundTool', function () {
            return new BetRoundTool;
        });
        $this->app->bind('RoundTool', function () {
            return new RoundTool;
        });
    }
}

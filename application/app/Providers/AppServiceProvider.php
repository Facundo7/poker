<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Round;
use App\Observers\RoundObserver;
use App\Models\Player;
use App\Observers\PlayerObserver;
use App\Models\Tournament;
use App\Observers\TournamentObserver;
use App\Models\BetRound;
use App\Observers\BetRoundObserver;
use App\Models\Action;
use App\Observers\ActionObserver;
use App\Models\RoundWinner;
use App\Observers\RoundWinnerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Round::observe(RoundObserver::class);
        Player::observe(PlayerObserver::class);
        Tournament::observe(TournamentObserver::class);
        BetRound::observe(BetRoundObserver::class);
        Action::observe(ActionObserver::class);
    }
}

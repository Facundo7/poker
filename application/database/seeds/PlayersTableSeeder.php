<?php

use Illuminate\Database\Seeder;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;


class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::make([
            [
                'tournament_id' => 1,
                'user_id' => 3,
                'stack' => 1000,
                'betting' => null,
                'sit' => 1,
            ],
            [
                'tournament_id' => 1,
                'user_id' => 6,
                'stack' => 1000,
                'betting' => null,
                'sit' => 2,
            ],
            [
                'tournament_id' => 1,
                'user_id' => 4,
                'stack' => 1000,
                'betting' => null,
                'sit' => 3,
            ],
            [
                'tournament_id' => 1,
                'user_id' => 5,
                'stack' => 1000,
                'betting' => null,
                'sit' => 4,
            ],
            [
                'tournament_id' => 1,
                'user_id' => 2,
                'stack' => 1000,
                'betting' => null,
                'sit' => 5,
            ],

        ])->each(function ($item) {

            Player::updateOrCreate(
                ['user_id' => $item['user_id']],
                $item
            );

        });
    }
}

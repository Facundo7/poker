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
                'id'=>1,
                'tournament_id' => 2,
                'user_id' => 5,
                'stack' => 1000,
                'betting' => 0,
                'sit' => 1,
            ],
            [
                'id'=>2,
                'tournament_id' => 2,
                'user_id' => 6,
                'stack' => 1000,
                'betting' => 0,
                'sit' => 2,
            ],
            [
                'id'=>3,
                'tournament_id' => 2,
                'user_id' => 4,
                'stack' => 1000,
                'betting' => 0,
                'sit' => 3,
            ],
            [
                'id'=>4,
                'tournament_id' => 1,
                'user_id' => 6,
                'stack' => 1000,
                'betting' => 0,
                'sit' => 1,
            ],
            // [
            //     'tournament_id' => 1,
            //     'user_id' => 4,
            //     'stack' => 1000,
            //     'betting' => 0,
            //     'sit' => 3,
            // ],
            [
                'id'=>5,
                'tournament_id' => 1,
                'user_id' => 5,
                'stack' => 1000,
                'betting' => 0,
                'sit' => 2,
            ],
            // [
            //     'tournament_id' => 1,
            //     'user_id' => 2,
            //     'stack' => 1000,
            //     'betting' => 0,
            //     'sit' => 5,
            // ],


        ])->each(function ($item) {

            Player::updateOrCreate(
                ['id' => $item['id']],
                $item
            );

        });
    }
}

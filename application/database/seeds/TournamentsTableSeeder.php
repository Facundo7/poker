<?php

use Illuminate\Database\Seeder;
use App\Models\Tournament;

class TournamentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tournament::create(
            [
                'id'=>1,
                'title'=>'torneo1',
                'bb'=>10,
                'bb_level'=>null,
                'bb_start_value'=>10,
                'bb_increase_time'=>5,
                'bb_increase_value'=>2,
                'initial_stack'=>1000,
                'players_number'=>5,
                'turn_seconds'=>30,
                'buy_in'=>5,
            ]
        );
    }
}
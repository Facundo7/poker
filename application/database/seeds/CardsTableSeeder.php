<?php

use Illuminate\Database\Seeder;
use App\Models\Card;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 2; $j <= 14; $j++) {
                $card=['suit'=>$i, 'value'=>$j];
                Card::updateOrCreate(
                    ['suit'=>$i, 'value'=>$j], $card
                );
            }
        }
    }
}

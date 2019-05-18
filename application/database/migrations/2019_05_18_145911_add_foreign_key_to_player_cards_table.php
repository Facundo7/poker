<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPlayerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player_cards', function (Blueprint $table) {
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_cards', function (Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropForeign(['card_id']);
            $table->dropForeign(['player_id']);
        });
    }
}

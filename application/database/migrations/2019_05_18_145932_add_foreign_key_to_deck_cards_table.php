<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToDeckCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deck_cards', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('card_id')->references('id')->on('cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deck_cards', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['card_id']);
        });
    }
}

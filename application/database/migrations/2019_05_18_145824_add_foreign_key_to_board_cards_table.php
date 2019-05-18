<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToBoardCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('board_cards', function (Blueprint $table) {
            $table->foreign('round_id')->references('id')->on('rounds');
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
        Schema::table('board_cards', function (Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropForeign(['card_id']);
        });
    }
}

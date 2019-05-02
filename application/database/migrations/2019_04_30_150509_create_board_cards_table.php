<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('card_id');
            $table->integer('position');
            $table->primary(['round_id', 'card_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_cards');
    }
}

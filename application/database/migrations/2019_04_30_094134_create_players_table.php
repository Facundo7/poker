<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('stack')->nullable();
            $table->unsignedInteger('betting')->nullable();
            $table->integer('sit')->nullable();
            $table->tinyInteger('bb')->nullable();
            $table->tinyInteger('sb')->nullable();
            $table->tinyInteger('button')->nullable();
            $table->tinyInteger('alive')->nullable();
            $table->tinyInteger('playing')->nullable();
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
        Schema::dropIfExists('players');
    }
}

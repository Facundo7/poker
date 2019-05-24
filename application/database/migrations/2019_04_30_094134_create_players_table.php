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
            $table->unsignedInteger('betting')->default(0);
            $table->integer('sit')->nullable();
            $table->tinyInteger('button')->default(false);
            $table->tinyInteger('bb')->default(false);
            $table->tinyInteger('sb')->default(false);
            $table->tinyInteger('alive')->default(true);
            $table->tinyInteger('playing')->default(false);
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

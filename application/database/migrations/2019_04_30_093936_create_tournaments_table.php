<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('title');
            $table->integer('bb');
            $table->integer('bb_level')->default(1);
            $table->integer('bb_start_value');
            $table->integer('bb_increase_time')->default(0);
            $table->integer('bb_increase_value')->default(0);
            $table->integer('initial_stack');
            $table->integer('players_number');
            $table->integer('turn_seconds');
            $table->decimal('buy_in');
            $table->tinyInteger('active')->default(true);
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
        Schema::dropIfExists('tournaments');
    }
}

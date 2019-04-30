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
            $table->string('title');
            $table->integer('BB');
            $table->integer('BB_level');
            $table->integer('BB_start_value');
            $table->integer('BB_increase_time');
            $table->integer('BB_increase_value');
            $table->integer('initial_stack');
            $table->integer('turn_seconds');
            $table->decimal('buy_in');
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

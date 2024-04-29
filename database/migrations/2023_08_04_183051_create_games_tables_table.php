<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games_tables', function (Blueprint $table) {
            $table->id();
            $table->string('player_id');
            $table->integer('win');
            $table->integer('loss');
            $table->integer('draw');
            $table->integer('points');
            $table->integer('gd')->default(0);
            $table->integer('tournament_id')->default(0);
            $table->timestamps();
        });
    }

    // game_id:id,
    // player_one_id:(playerOneId===0)?game.player_one_id:playerOneId,
    // player_one_score:(playerOneScore===0)?game.player_one_score:playerOneScore,
    // player_two_id:(playerTwoId===0)?game.player_two_id:playerTwoId,
    // player_two_score:(playerTwoScore===0)?game.player_two_score:playerTwoScore,
    // tournament_id:game.tournament_id

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games_tables');
    }
};

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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('t_name');
            $table->string('t_prize')->default('0');
            $table->string('user_id')->default('0');
            $table->string('t_image_name')->nullable();
            $table->string('t_desc')->nullable();
            $table->integer('number_of_players')->default(0);
            $table->string('status')->default('open');
            $table->string('winner')->default('0');
            $table->string('winner_points')->default(0);
            $table->string('has_second_leg')->default('1');
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
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tournament_id');
            $table->foreignId('fecha_id')->nullable();
            $table->foreignId('category_id');
            $table->foreignId('team_id_1');
            $table->foreignId('team_id_2');
            $table->foreignId('team_id_winner')->nullable();
            $table->dateTime('horario')->nullable();
            $table->integer('cancha')->nullable();
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
        Schema::dropIfExists('matches');
    }
}

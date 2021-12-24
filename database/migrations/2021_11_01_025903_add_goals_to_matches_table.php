<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoalsToMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('team_1_goals')->after('team_id_2');
            $table->integer('team_2_goals')->after('team_1_goals');
            $table->integer('team_id_winner')->default(0)->change();
            $table->boolean('finished')->default(0)->after('cancha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('team_1_goals');
            $table->dropColumn('team_2_goals');
            $table->dropColumn('finished');
        });
    }
}

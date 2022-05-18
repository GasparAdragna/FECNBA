<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('motive');
            $table->string('sanction');
            $table->boolean('active')->default(true);
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('fecha_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('sanctions');
    }
}

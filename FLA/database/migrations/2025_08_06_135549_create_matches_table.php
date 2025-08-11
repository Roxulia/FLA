<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
             $table->id('match_id'); // Primary Key

            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->date('date');
            $table->time('time');

            $table->string('score', 10)->nullable();

            $table->enum('status', ['scheduled', 'live', 'finished', 'postponed', 'cancelled']);

            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('id_from_api')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('home_team_id')->references('team_id')->on('teams')->onDelete('cascade');
            $table->foreign('away_team_id')->references('team_id')->on('teams')->onDelete('cascade');
            $table->foreign('league_id')->references('league_id')->on('leagues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};

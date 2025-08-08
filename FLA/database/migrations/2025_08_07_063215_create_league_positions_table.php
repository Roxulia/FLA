<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('league_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('league_id');
            $table->smallInteger('team_position',unsigned: true)->default(0);
            $table->smallInteger('played_matches',unsigned: true)->default(0);
            $table->smallInteger('wins',unsigned: true)->default(0);
            $table->smallInteger('losses',unsigned: true)->default(0);
            $table->smallInteger('draws',unsigned: true)->default(0);
            $table->smallInteger('goal_given',unsigned: true)->default(0);
            $table->smallInteger('goal_achieved',unsigned:true)->default(0);
            $table->smallInteger('points')->default(0);
            $table->smallInteger('home_wins')->default(0);
            $table->smallInteger('away_wins')->default(0);
            $table->enum('streak_type',['Winning','Losing','None'])->default('None');
            $table->smallInteger('streak_count',unsigned:true)->default(0);
            $table->unsignedBigInteger('next_match_id')->nullable();
            $table->dateTime('last_updated')->default(DB::raw('CURRENT_TIMESTAMP')) ->useCurrentOnUpdate();

            $table->timestamps();
            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
            $table->foreign('league_id')->references('league_id')->on('leagues')->onDelete('cascade');
            $table->foreign('next_match_id')->references('match_id')->on('matches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_positions');
    }
};

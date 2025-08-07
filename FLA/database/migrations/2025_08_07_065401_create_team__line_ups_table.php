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
        Schema::create('team__line_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('match_id');
            $table->enum('status',['Main', 'Sub', 'Injured', 'Suspended', 'Rested'])->default('Main');//Add More Option Please
            $table->unsignedSmallInteger('score')->default(0);
            $table->unsignedSmallInteger('yellow_card')->default(0);
            $table->unsignedSmallInteger('red_card')->default(0);
            $table->timestamps();

            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
            $table->foreign('player_id')->references('player_id')->on('players')->onDelete('cascade');
            $table->foreign('match_id')->references('match_id')->on('matches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team__line_ups');
    }
};

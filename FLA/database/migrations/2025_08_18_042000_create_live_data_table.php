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
        Schema::create('live_data', function (Blueprint $table) {
            $table->id();
            $table->string('live_id')->unique();
            $table->string('home_name',50);
            $table->string('home_logo',255);
            $table->string('away_name',50);
            $table->string('away_logo',255);
            $table->smallInteger('home_score')->default(0);
            $table->smallInteger('away_score')->default(0);
            $table->string('video_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_data');
    }
};

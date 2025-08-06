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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id('league_id');
            $table->string('fullname', 50); // Required
            $table->string('shortform', 20)->nullable(); // Optional
            $table->string('code', 10)->nullable(); // Optional
            $table->string('country', 20)->nullable(); // Optional

            $table->enum('type', ['domestic', 'international'])->nullable(); // Adjust options
            $table->enum('tier', ['first', 'second', 'third'])->nullable(); // Adjust options

            $table->date('season_start')->nullable();
            $table->date('season_end')->nullable();

            $table->integer('current_season')->nullable();
            $table->string('logo', 255)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};

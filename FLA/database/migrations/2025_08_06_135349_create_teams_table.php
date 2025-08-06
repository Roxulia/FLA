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
        Schema::create('teams', function (Blueprint $table) {
            $table->id('team_id');
            $table->string('team_fullname', 50); // Required
            $table->string('team_shortform', 20)->nullable(); // Optional
            $table->string('team_code', 10)->nullable(); // Optional
            $table->string('country', 20)->nullable(); // Optional
            $table->string('city', 20)->nullable();
            $table->string('stadium_name',50)->nullable();
            $table->date('found_year')->nullable();
            $table->string('logo', 255)->nullable();
            $table->boolean('is_national')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

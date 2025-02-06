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
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_one_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('player_two_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('players')->onDelete('set null');
            $table->integer('round');
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');  // Relación con el torneo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};

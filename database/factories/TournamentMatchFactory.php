<?php

namespace Database\Factories;

use App\Models\TournamentMatch;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentMatchFactory extends Factory
{
    protected $model = TournamentMatch::class;

    public function definition()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();

        return [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'winner_id' => null,
            'round' => 1,
            'tournament_id' => Tournament::factory()->create()->id
        ];
    }
}

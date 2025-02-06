<?php

namespace Tests\Unit\Services;

use App\Services\TournamentMatchService;
use App\Models\TournamentMatch;
use App\Models\Player;
use App\Models\Tournament;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentMatchServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de un partido con datos válidos.
     */
    public function test_create_tournament_match_with_valid_data()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'round' => 1,
            'tournament_id' => $tournament->id
        ];

        $match = TournamentMatchService::createTournamentMatch($data);

        $this->assertInstanceOf(TournamentMatch::class, $match);
        $this->assertDatabaseHas('tournament_matches', [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'round' => 1,
            'tournament_id' => $tournament->id
        ]);
    }

    /**
     * Prueba la creación de un partido con datos inválidos.
     */
    public function test_create_tournament_match_with_invalid_data()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $data = [
            'player_one_id' => null,
            'player_two_id' => null,
            'round' => -1,
            'tournament_id' => null
        ];

        TournamentMatchService::createTournamentMatch($data);
    }

    /**
     * Prueba la actualización de un partido.
     */
    public function test_update_tournament_match()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();
        $match = TournamentMatch::factory()->create();
        $tournament = Tournament::factory()->create();

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'round' => 2,
            'tournament_id' => $tournament->id
        ];

        $updatedMatch = TournamentMatchService::updateTournamentMatch($match->id, $data);

        $this->assertEquals(2, $updatedMatch->round);
        $this->assertDatabaseHas('tournament_matches', ['round' => 2]);
    }    
}

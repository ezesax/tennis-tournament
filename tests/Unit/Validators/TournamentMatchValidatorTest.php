<?php

namespace Tests\Unit;

use App\Validators\TournamentMatchValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use App\Models\Player;
use App\Models\Tournament;

class TournamentMatchValidatorTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Prueba con datos válidos.
     */
    public function test_valid_match_data()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'winner_id' => $playerOne->id,
            'round' => 1,
            'tournament_id' => $tournament->id
        ];

        $result = TournamentMatchValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar con datos válidos.');
        $this->assertEquals(1, $result['player_one_id']);
        $this->assertEquals(2, $result['player_two_id']);
    }

    /**
     * Prueba con datos inválidos: falta el campo `player_one_id`.
     */
    public function test_missing_player_one_id()
    {
        $playerTwo = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $this->expectException(ValidationException::class);

        $data = [
            'player_two_id' => $playerTwo->id,
            'winner_id' => $playerTwo->id,
            'round' => 1,
            'tournament_id' => $tournament->id
        ];

        TournamentMatchValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: los dos jugadores son el mismo.
     */
    public function test_players_are_the_same()
    {
        $playerOne = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $this->expectException(ValidationException::class);

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerOne->id,
            'winner_id' => $playerOne->id,
            'round' => 1,
            'tournament_id' => $tournament->id
        ];

        TournamentMatchValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: falta el campo `tournament_id`.
     */
    public function test_missing_tournament_id()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();

        $this->expectException(ValidationException::class);

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'winner_id' => $playerOne->id,
            'round' => 1
        ];

        TournamentMatchValidator::validate($data);
    }

    /**
     * Prueba con `winner_id` nulo.
     */
    public function test_nullable_winner_id()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'winner_id' => null,
            'round' => 1,
            'tournament_id' => $tournament->id
        ];

        $result = TournamentMatchValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar cuando `winner_id` es nulo.');
        $this->assertNull($result['winner_id']);
    }

    /**
     * Prueba con un valor inválido para el campo `round`.
     */
    public function test_invalid_round_value()
    {
        $playerOne = Player::factory()->create();
        $playerTwo = Player::factory()->create();
        $tournament = Tournament::factory()->create();

        $this->expectException(ValidationException::class);

        $data = [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'winner_id' => $playerOne->id,
            'round' => 0,
            'tournament_id' => $tournament->id
        ];

        TournamentMatchValidator::validate($data);
    }
}

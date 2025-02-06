<?php

namespace Tests\Unit\Services;

use App\Services\SimulationService;
use App\Models\Player;
use App\Models\TournamentMatch;
use App\Models\Tournament;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimulationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SimulationService $simulationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simulationService = new SimulationService();
    }

    /**
     * Prueba la simulación de un partido con jugadores válidos.
     */
    public function test_simulate_match_with_valid_players()
    {
        $playerOne = Player::factory()->create(['skill_level' => 90, 'strength' => 85, 'speed' => 80]);
        $playerTwo = Player::factory()->create(['skill_level' => 85, 'strength' => 78, 'speed' => 82]);
        $match = TournamentMatch::factory()->create([
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'tournament_id' => Tournament::factory()->create()->id,
        ]);

        $winnerId = $this->simulationService->simulateMatch($match, 'male');

        $this->assertTrue(in_array($winnerId, [$playerOne->id, $playerTwo->id]));
        $this->assertDatabaseHas('tournament_matches', ['id' => $match->id, 'winner_id' => $winnerId]);
    }

    /**
     * Prueba que el cálculo de habilidad funcione correctamente para jugadores masculinos.
     */
    public function test_calculate_skill_for_male_player()
    {
        $player = Player::factory()->create([
            'skill_level' => 80,
            'strength' => 70,
            'speed' => 90
        ]);

        $method = new \ReflectionMethod(SimulationService::class, 'calculateSkill');
        $method->setAccessible(true);

        $calculatedSkill = $method->invoke($this->simulationService, $player, 'male');

        $this->assertGreaterThan(80, $calculatedSkill);
    }

    /**
     * Prueba que el cálculo de habilidad funcione correctamente para jugadores femeninos.
     */
    public function test_calculate_skill_for_female_player()
    {
        $player = Player::factory()->create([
            'skill_level' => 75,
            'reaction_time' => 85
        ]);

        $method = new \ReflectionMethod(SimulationService::class, 'calculateSkill');
        $method->setAccessible(true);

        $calculatedSkill = $method->invoke($this->simulationService, $player, 'female');

        $this->assertGreaterThan(75, $calculatedSkill);
    }

    /**
     * Prueba que no se puedan simular partidos con jugadores inválidos.
     */
    public function test_simulate_match_with_invalid_players()
    {
        $this->expectException(\Exception::class);

        $match = TournamentMatch::factory()->create([
            'player_one_id' => 9999,
            'player_two_id' => 9999,
            'tournament_id' => Tournament::factory()->create()->id,
        ]);

        $this->simulationService->simulateMatch($match, 'male');
    }
}

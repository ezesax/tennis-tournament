<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MainController;
use App\Services\SimulationService;
use App\Services\TournamentService;
use App\Services\TournamentMatchService;
use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class MainControllerTest extends TestCase
{
    use RefreshDatabase;

    protected MainController $controller;
    protected SimulationService $simulationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->simulationService = Mockery::mock(SimulationService::class);
        $this->controller = new MainController($this->simulationService);
    }

    /**
     * Prueba la simulación de un torneo con jugadores válidos.
     */
    public function test_simulate_with_valid_data()
    {
        $playersData = [
            ['id' => 1, 'name' => 'Player 1', 'skill_level' => 80, 'gender' => 'male'],
            ['id' => 2, 'name' => 'Player 2', 'skill_level' => 75, 'gender' => 'male']
        ];

        $tournament = Tournament::factory()->create(['status' => 'not_started']);
        $this->simulationService->shouldReceive('simulateMatch')->andReturn($playersData[0]['id']);

        $request = Request::create('/api/simulate', 'POST', [
            'players' => $playersData,
            'gender' => 'male'
        ]);

        $response = $this->controller->simulate($request);

        $this->assertDatabaseHas('tournaments', ['status' => 'completed']);
    }
}

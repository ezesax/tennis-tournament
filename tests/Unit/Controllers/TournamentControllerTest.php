<?php

namespace Tests\Unit\Controllers;

use App\Models\Tournament;
use App\Http\Controllers\TournamentController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TournamentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la obtención de torneos completados con parámetros de fecha y género.
     */
    public function test_get_completed_tournaments_with_date_and_gender()
    {
        Tournament::factory()->create(['name' => 'Wimbledon', 'status' => 'completed', 'gender' => 'male', 'created_at' => now()]);
        Tournament::factory()->create(['name' => 'Tennis', 'status' => 'completed', 'gender' => 'female', 'created_at' => now()->subDay()]);

        $request = Request::create('/api/tournaments/completed', 'GET', [
            'date' => now()->format('Y-m-d'),
            'gender' => 'male'
        ]);

        $controller = new TournamentController();
        $response = $controller->getCompletedTournaments($request);

        $this->assertCount(1, $response->getData()->data);
        $this->assertEquals('Wimbledon', $response->getData()->data[0]->name);
    }

    /**
     * Prueba la obtención de torneos completados solo por fecha.
     */
    public function test_get_completed_tournaments_with_only_date()
    {
        Tournament::factory()->create(['name' => 'Wimbledon', 'status' => 'completed', 'gender' => 'male', 'created_at' => now()]);
        Tournament::factory()->create(['name' => 'Tennis', 'status' => 'completed', 'gender' => 'female', 'created_at' => now()]);

        $request = Request::create('/api/tournaments/completed', 'GET', [
            'date' => now()->format('Y-m-d')
        ]);

        $controller = new TournamentController();
        $response = $controller->getCompletedTournaments($request);

        $this->assertCount(2, $response->getData()->data);
    }

    /**
     * Prueba la obtención de torneos completados solo por género.
     */
    public function test_get_completed_tournaments_with_only_gender()
    {
        Tournament::factory()->create(['name' => 'Wimbledon', 'status' => 'completed', 'gender' => 'male']);
        Tournament::factory()->create(['name' => 'Tennis', 'status' => 'completed', 'gender' => 'female']);

        $request = Request::create('/api/tournaments/completed', 'GET', [
            'gender' => 'female'
        ]);

        $controller = new TournamentController();
        $response = $controller->getCompletedTournaments($request);

        $this->assertCount(1, $response->getData()->data);
        $this->assertEquals('Tennis', $response->getData()->data[0]->name);
    }

    /**
     * Prueba la obtención de todos los torneos completados sin filtros.
     */
    public function test_get_completed_tournaments_without_filters()
    {
        Tournament::factory()->create(['name' => 'Wimbledon', 'status' => 'completed']);
        Tournament::factory()->create(['name' => 'Tennis', 'status' => 'completed']);

        $request = Request::create('/api/tournaments/completed', 'GET', []);

        $controller = new TournamentController();
        $response = $controller->getCompletedTournaments($request);

        $this->assertCount(2, $response->getData()->data);
    }
}

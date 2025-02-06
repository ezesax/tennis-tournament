<?php

namespace Tests\Unit\Services;

use App\Services\TournamentService;
use App\Models\Tournament;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class TournamentServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de un torneo con datos válidos.
     */
    public function test_create_tournament_with_valid_data()
    {
        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'not_started'
        ];

        $tournament = TournamentService::createTournament($data);

        $this->assertInstanceOf(Tournament::class, $tournament);
        $this->assertDatabaseHas('tournaments', ['name' => 'Tennis']);
    }

    /**
     * Prueba la creación de un torneo con datos inválidos.
     */
    public function test_create_tournament_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'gender' => 'unknown',
            'status' => 'invalid_status'
        ];

        TournamentService::createTournament($data);
    }

    /**
     * Prueba la actualización de un torneo.
     */
    public function test_update_tournament()
    {
        $tournament = Tournament::factory()->create(['name' => 'Tennis']);

        $data = [
            'name' => 'Tennis - Bs As',
            'status' => 'completed',
            'gender' => 'male'
        ];

        $updatedTournament = TournamentService::updateTournament($tournament->id, $data);

        $this->assertEquals('Tennis - Bs As', $updatedTournament->name);
        $this->assertDatabaseHas('tournaments', ['name' => 'Tennis - Bs As']);
    }

    /**
     * Prueba la actualización de un torneo con datos inválidos.
     */
    public function test_update_tournament_with_invalid_data()
    {
        $tournament = Tournament::factory()->create();

        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'gender' => 'wrong_gender'
        ];

        TournamentService::updateTournament($tournament->id, $data);
    }
}

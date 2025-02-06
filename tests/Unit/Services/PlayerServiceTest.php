<?php

namespace Tests\Unit\Services;

use App\Services\PlayerService;
use App\Models\Player;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class PlayerServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de un jugador con datos válidos.
     */
    public function test_create_player_with_valid_data()
    {
        $data = [
            'name' => 'Juan Perez',
            'skill_level' => 95,
            'strength' => 85,
            'speed' => 88,
            'reaction_time' => 80,
            'gender' => 'male'
        ];

        $player = PlayerService::createPlayer($data);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertDatabaseHas('players', ['name' => 'Juan Perez']);
    }

    /**
     * Prueba la creación de un jugador con datos inválidos.
     */
    public function test_create_player_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'skill_level' => 150,
            'gender' => 'unknown'
        ];

        PlayerService::createPlayer($data);
    }

    /**
     * Prueba la actualización de un jugador.
     */
    public function test_update_player()
    {
        $player = Player::factory()->create(['name' => 'Juan Pablo Perez']);

        $data = [
            'name' => 'Juanpa Perez',
            'skill_level' => 90,
            'gender' => 'male'
        ];

        $updatedPlayer = PlayerService::updatePlayer($player->id, $data);

        $this->assertEquals('Juanpa Perez', $updatedPlayer->name);
        $this->assertDatabaseHas('players', ['name' => 'Juanpa Perez']);
    }
}

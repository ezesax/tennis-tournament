<?php

namespace Tests\Unit;

use App\Validators\TournamentValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use App\Models\Player;

class TournamentValidatorTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Prueba con datos válidos de un torneo.
     */
    public function test_valid_tournament_data()
    {
        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'not_started'
        ];

        $result = TournamentValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar con datos válidos.');
        $this->assertEquals('Tennis', $result['name']);
        $this->assertEquals('male', $result['gender']);
    }

    /**
     * Prueba con datos inválidos: falta el campo `name`.
     */
    public function test_missing_name_field()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'gender' => 'male',
            'status' => 'not_started'
        ];

        TournamentValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: valor incorrecto en el campo `gender`.
     */
    public function test_invalid_gender_value()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => 'Tennis',
            'gender' => 'unknown',
            'status' => 'not_started'
        ];

        TournamentValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: falta el campo `status`.
     */
    public function test_missing_status_field_is_nullable()
    {
        $data = [
            'name' => 'Tennis',
            'gender' => 'male'
        ];

        $result = TournamentValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar cuando falta el campo `status`.');
    }

    /**
     * Prueba con un campo `status` no válido.
     */
    public function test_invalid_status_value()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'invalid_status'
        ];

        TournamentValidator::validate($data);
    }

    /**
     * Prueba con un array vacío.
     */
    public function test_empty_data()
    {
        $this->expectException(ValidationException::class);

        TournamentValidator::validate([]);
    }

    /**
     * Prueba con campos adicionales que no están definidos en la validación.
     */
    public function test_extra_fields_are_ignored()
    {
        $data = [
            'name' => 'Tennis',
            'gender' => 'female',
            'status' => 'not_started',
            'extra_field' => 'should_be_ignored'
        ];

        $result = TournamentValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería ignorar campos adicionales.');
        $this->assertArrayNotHasKey('extra_field', $result, 'El campo adicional `extra_field` no debería estar presente en el resultado.');
    }

    /**
     * Prueba con un `winner_id` válido.
     */
    public function test_valid_winner_id()
    {
        // Crea un jugador para asociarlo como ganador
        $player = Player::factory()->create();

        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'completed',
            'winner_id' => $player->id
        ];

        $result = TournamentValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar con un `winner_id` válido.');
        $this->assertEquals($player->id, $result['winner_id']);
    }

    /**
     * Prueba con un `winner_id` que no existe en la base de datos.
     */
    public function test_nonexistent_winner_id()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'completed',
            'winner_id' => 9999
        ];

        TournamentValidator::validate($data);
    }

    /**
     * Prueba con un `winner_id` nulo.
     */
    public function test_nullable_winner_id()
    {
        $data = [
            'name' => 'Tennis',
            'gender' => 'male',
            'status' => 'not_started',
            'winner_id' => null
        ];

        $result = TournamentValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar cuando `winner_id` es nulo.');
        $this->assertNull($result['winner_id']);
    }
}

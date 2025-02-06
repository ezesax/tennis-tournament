<?php

namespace Tests\Unit;

use App\Validators\PlayerValidator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PlayerValidatorTest extends TestCase
{
    /**
     * Prueba con datos de jugador válidos.
     */
    public function test_valid_player_data()
    {
        $data = [
            'name' => 'Juan Perez',
            'skill_level' => 95,
            'strength' => 85,
            'speed' => 88,
            'reaction_time' => 80,
            'gender' => 'male'
        ];

        $result = PlayerValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar con datos válidos.');
        $this->assertEquals('Juan Perez', $result['name']);
    }

    /**
     * Prueba con datos inválidos: falta el nombre.
     */
    public function test_missing_name_field()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'skill_level' => 95,
            'strength' => 85,
            'speed' => 88,
            'reaction_time' => 80,
            'gender' => 'male'
        ];

        PlayerValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: `skill_level` fuera de rango.
     */
    public function test_skill_level_out_of_range()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => 'Juan Perez',
            'skill_level' => 120,
            'strength' => 85,
            'speed' => 88,
            'reaction_time' => 80,
            'gender' => 'male'
        ];

        PlayerValidator::validate($data);
    }

    /**
     * Prueba con datos inválidos: valor no válido para `gender`.
     */
    public function test_invalid_gender_value()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => 'Juan Perez',
            'skill_level' => 95,
            'strength' => 85,
            'speed' => 88,
            'reaction_time' => 80,
            'gender' => 'unknown'
        ];

        PlayerValidator::validate($data);
    }

    /**
     * Prueba con datos parcialmente válidos (algunos campos nulos).
     */
    public function test_nullable_fields()
    {
        $data = [
            'name' => 'Juan Perez',
            'skill_level' => 95,
            'strength' => null,
            'speed' => null,
            'reaction_time' => null,
            'gender' => 'male'
        ];

        $result = PlayerValidator::validate($data);

        $this->assertIsArray($result, 'La validación debería pasar cuando los campos nulos están permitidos.');
        $this->assertNull($result['strength']);
        $this->assertNull($result['speed']);
        $this->assertNull($result['reaction_time']);
    }

    /**
     * Prueba con un array vacío (datos completamente ausentes).
     */
    public function test_empty_data()
    {
        $this->expectException(ValidationException::class);

        PlayerValidator::validate([]);
    }
}

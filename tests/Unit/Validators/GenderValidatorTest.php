<?php

namespace Tests\Unit;

use App\Validators\GenderValidator;
use Tests\TestCase;

class GenderValidatorTest extends TestCase
{
    /**
     * Prueba con datos válidos donde todos los jugadores tienen el mismo género.
     */
    public function test_valid_gender_data()
    {
        $data = [
            ['gender' => 'male'],
            ['gender' => 'male'],
            ['gender' => 'male']
        ];

        $result = GenderValidator::validate($data);
        $this->assertTrue($result, 'La validación debería pasar cuando todos los jugadores tienen el mismo género.');
    }

    /**
     * Prueba con datos donde los jugadores tienen géneros diferentes.
     */
    public function test_invalid_gender_data()
    {
        $data = [
            ['gender' => 'male'],
            ['gender' => 'female']
        ];

        $result = GenderValidator::validate($data);
        $this->assertFalse($result, 'La validación debería fallar cuando hay diferentes géneros.');
    }

    /**
     * Prueba con un array vacío.
     */
    public function test_empty_data_array()
    {
        $data = [];

        $result = GenderValidator::validate($data);
        $this->assertFalse($result, 'La validación debería fallar cuando el array está vacío.');
    }

    /**
     * Prueba con datos que no contienen el campo `gender`.
     */
    public function test_data_missing_gender_field()
    {
        $data = [
            ['name' => 'Juan Perez'],
            ['name' => 'Pablo Osorio']
        ];

        $result = GenderValidator::validate($data);
        $this->assertFalse($result, 'La validación debería fallar si los datos no contienen el campo `gender`.');
    }
}

<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PlayerValidator
{
    /**
     * Valida los datos de un jugador.
     *
     * @param array $data Datos a validar.
     * @return array Datos validados.
     * @throws ValidationException Si los datos no pasan la validación.
     */
    public static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'skill_level' => 'required|integer|min:0|max:100',
            'strength' => 'nullable|integer|min:0|max:100',
            'speed' => 'nullable|integer|min:0|max:100',
            'reaction_time' => 'nullable|integer|min:0|max:100',
            'gender' => 'required|in:male,female'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

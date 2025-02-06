<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TournamentValidator
{
    /**
     * Valida los datos de un torneo.
     *
     * @param array $data Datos a validar.
     * @return array Datos validados.
     * @throws ValidationException Si los datos no pasan la validación.
     */
    public static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'status' => 'nullable|in:not_started,in_progress,completed',
            'winner_id' => 'nullable|exists:players,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TournamentMatchValidator
{
    /**
     * Valida los datos de un partido de torneo.
     *
     * @param array $data Datos a validar.
     * @return array Datos validados.
     * @throws ValidationException Si los datos no pasan la validación.
     */
    public static function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'player_one_id' => 'required|exists:players,id',
            'player_two_id' => 'required|exists:players,id|different:player_one_id',
            'winner_id' => 'nullable|exists:players,id',
            'round' => 'required|integer|min:1',
            'tournament_id' => 'required|exists:tournaments,id'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

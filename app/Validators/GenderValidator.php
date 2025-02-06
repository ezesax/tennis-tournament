<?php

namespace App\Validators;

class GenderValidator
{
    /**
     * Valida que todos los participantes del torneo sean del mismo genero.
     *
     * @param array $data Array de jugadores (objetos json).
     * @return bool Resultado de la validacion
     */

     public static function validate(array $data): bool
     {
        $genders = array_column($data, 'gender');

        if (count(array_unique($genders)) !== 1) {
            return false;
        }

        return true;
     }
}
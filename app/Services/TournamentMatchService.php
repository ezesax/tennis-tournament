<?php

namespace App\Services;

use App\Models\TournamentMatch;
use App\Validators\TournamentMatchValidator;

class TournamentMatchService
{
    /**
     * Crea un partido.
     *
     * @param array $data
     * @return TournamentMatch
     */

     public static function createTournamentMatch(array $data): TournamentMatch
     {
        $validated = TournamentMatchValidator::validate($data);

        return TournamentMatch::create($validated);
     }

     /**
     * Actualiza un partido.
     *
     * @param int $id
     * @param array $data
     * @return TournamentMatch
     */

     public static function updateTournamentMatch(int $id, array $data): TournamentMatch
     {
        $validated = TournamentMatchValidator::validate($data);
        $tournamentMatch = TournamentMatch::find($id);
        $tournamentMatch->update($validated);

        return $tournamentMatch;
     }
}
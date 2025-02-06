<?php

namespace App\Services;

use App\Models\Tournament;
use App\Validators\TournamentValidator;

class TournamentService
{
    /**
     * Crea un torneo.
     *
     * @param array $data
     * @return Tournament
     */

     public static function createTournament(array $data): Tournament
     {
        $validated = TournamentValidator::validate($data);

        return Tournament::create($validated);
     }

     /**
     * Actualiza un torneo.
     *
     * @param int $id
     * @param array $data
     * @return Tournament
     */

     public static function updateTournament(int $id, array $data): Tournament
     {
        $validated = TournamentValidator::validate($data);
        $tournament = Tournament::find($id);
        $tournament->update($validated);

        return $tournament;
     }
}

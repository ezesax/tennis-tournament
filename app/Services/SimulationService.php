<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class SimulationService
{
    /**
     * Simula un partido y devuelve el ID del jugador ganador.
     *
     * @param TournamentMatch $match Instancia del partido.
     * @param string $gender Género del torneo ('male' o 'female').
     * @return int ID del jugador ganador.
     */
    public function simulateMatch(TournamentMatch $match, string $gender): int
    {
        $playerOne = $match->playerOne;
        $playerTwo = $match->playerTwo;

        $playerOneSkill = $this->calculateSkill($playerOne, $gender);
        $playerTwoSkill = $this->calculateSkill($playerTwo, $gender);

        $winner = $playerOneSkill > $playerTwoSkill ? $playerOne : $playerTwo;

        $match->update(['winner_id' => $winner->id]);

        return $winner->id;
    }

    /**
     * Calcula la habilidad de un jugador basado en el género.
     *
     * @param Player $player Jugador.
     * @param string $gender Género del torneo ('male' o 'female').
     * @return float Puntuación calculada del jugador.
     */
    private function calculateSkill($player, string $gender): float
    {
        $skill = $player->skill_level;

        if ($gender === 'male') {
            $skill += ($player->strength ?? 0) * 0.4;
            $skill += ($player->speed ?? 0) * 0.6;
        } elseif ($gender === 'female') {
            $skill += ($player->reaction_time ?? 0) * 0.5;
        }

        $luck = rand(0, 10);

        return $skill + $luck;
    }
}

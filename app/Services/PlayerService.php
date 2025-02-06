<?php

namespace App\Services;

use App\Models\Player;
use App\Validators\PlayerValidator;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
   /**
     * Obtiene jugadores por ids.
     *
     * @param array $ids
     * @return Collection
     */

     public static function getPlayers(array $ids): Collection
     {
      return Player::whereIn('id', $ids)->get();
     }

   /**
     * Crea un jugador.
     *
     * @param array $data
     * @return Player
     */

     public static function createPlayer(array $data): Player
     {
        $validated = PlayerValidator::validate($data);

        return Player::create($validated);
     }

     /**
     * Actualiza un jugador.
     *
     * @param int $id
     * @param array $data
     * @return Player
     */

     public static function updatePlayer(int $id, array $data): Player
     {
        $validated = PlayerValidator::validate($data);
        $player = Player::find($id);
        $player->update($validated);

        return $player;
     }
}
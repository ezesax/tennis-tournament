<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentMatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'player_one_id',
        'player_two_id',
        'winner_id',
        'round',
        'tournament_id'
    ];

    public function playerOne()
    {
        return $this->belongsTo(Player::class, 'player_one_id');
    }

    public function playerTwo()
    {
        return $this->belongsTo(Player::class, 'player_two_id');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}

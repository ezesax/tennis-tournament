<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'skill_level',
        'gender',
        'strength',
        'speed',
        'reaction_time'
    ];

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'player_tournament')
                    ->withTimestamps();
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class, 'winner_id');
    }
}

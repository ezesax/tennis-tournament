<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'status',
        'winner_id',
        'created_at',
        'updated_at'
    ];

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_tournament')
                    ->withTimestamps();
    }
}

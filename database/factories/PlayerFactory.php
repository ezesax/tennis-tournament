<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'skill_level' => $this->faker->numberBetween(50, 100),
            'strength' => $this->faker->numberBetween(0, 100),
            'speed' => $this->faker->numberBetween(0, 100),
            'reaction_time' => $this->faker->numberBetween(0, 100),
            'gender' => 'male'
        ];
    }
}

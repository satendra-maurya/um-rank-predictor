<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<State>
 */
class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'name' => fake()->state(),
            'short_name' => strtoupper(fake()->lexify('??')),
            'code' => strtoupper(fake()->unique()->lexify('??')),
            'status' => ActiveStatus::ACTIVE,
        ];
    }
}

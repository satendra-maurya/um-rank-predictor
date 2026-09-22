<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 10),
            'status' => ActiveStatus::ACTIVE,
        ];
    }
}

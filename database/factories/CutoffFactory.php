<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Cutoff;
use App\Models\ExamStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cutoff>
 */
class CutoffFactory extends Factory
{
    protected $model = Cutoff::class;

    public function definition(): array
    {
        return [
            'exam_stage_id' => ExamStage::factory(),
            'category_id' => Category::factory(),
            'post_name' => fake()->jobTitle(),
            'cutoff_marks' => fake()->randomFloat(2, 50, 150),
            'cutoff_rank' => fake()->numberBetween(1, 5000),
        ];
    }
}

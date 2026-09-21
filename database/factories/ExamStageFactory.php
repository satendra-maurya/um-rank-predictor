<?php

namespace Database\Factories;

use App\Models\ExamCycle;
use App\Models\ExamStage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ExamStage>
 */
class ExamStageFactory extends Factory
{
    protected $model = ExamStage::class;

    public function definition(): array
    {
        $name = 'Tier '.fake()->numberBetween(1, 3);

        return [
            'exam_cycle_id' => ExamCycle::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'stage_order' => fake()->numberBetween(1, 5),
            'type' => 'CBT',
            'description' => fake()->sentence(),
            'status' => 'ACTIVE',
        ];
    }
}

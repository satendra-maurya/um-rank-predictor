<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PredictionModel>
 */
class PredictionModelFactory extends Factory
{
    protected $model = PredictionModel::class;

    public function definition(): array
    {
        return [
            'exam_stage_id' => ExamStage::factory(),
            'name' => fake()->word().' Rank Engine',
            'version' => 'v'.fake()->unique()->numberBetween(1, 99).'.0',
            'total_marks' => 200.00,
            'negative_marking_ratio' => 0.25,
            'formula_config' => ['algorithm' => 'percentile_rank_v1'],
            'status' => ActiveStatus::ACTIVE,
        ];
    }
}

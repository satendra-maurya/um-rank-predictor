<?php

namespace Database\Factories;

use App\Models\CandidateSubmission;
use App\Models\PredictionResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PredictionResult>
 */
class PredictionResultFactory extends Factory
{
    protected $model = PredictionResult::class;

    public function definition(): array
    {
        return [
            'candidate_submission_id' => CandidateSubmission::factory(),
            'predicted_rank_overall' => fake()->numberBetween(1, 10000),
            'predicted_rank_category' => fake()->numberBetween(1, 2500),
            'percentile' => fake()->randomFloat(2, 50, 99.9),
            'confidence_score' => 95.50,
            'metadata' => ['total_crowd_samples' => 12500],
            'calculated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\Category;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CandidateSubmission>
 */
class CandidateSubmissionFactory extends Factory
{
    protected $model = CandidateSubmission::class;

    public function definition(): array
    {
        $attempted = fake()->numberBetween(70, 100);
        $correct = fake()->numberBetween(50, $attempted);
        $incorrect = $attempted - $correct;
        $rawScore = ($correct * 2) - ($incorrect * 0.5);

        return [
            'prediction_model_id' => PredictionModel::factory(),
            'exam_stage_id' => function (array $attributes) {
                return PredictionModel::find($attributes['prediction_model_id'])?->exam_stage_id ?? ExamStage::factory();
            },
            'shift_id' => Shift::factory(),
            'category_id' => Category::factory(),
            'user_id' => null,
            'candidate_identifier' => (string) fake()->numberBetween(1000000, 9999999),
            'total_attempted' => $attempted,
            'correct_answers' => $correct,
            'incorrect_answers' => $incorrect,
            'raw_score' => $rawScore,
            'normalized_score' => $rawScore + fake()->randomFloat(2, -5, 5),
            'session_token' => fake()->uuid(),
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'user_agent_hash' => hash('sha256', fake()->userAgent()),
            'device_fingerprint' => fake()->sha256(),
            'risk_score' => 0,
            'trust_status' => SubmissionTrustStatus::TRUSTED,
            'submitted_at' => now(),
        ];
    }
}

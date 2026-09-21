<?php

namespace Database\Factories;

use App\Models\CandidateSubmission;
use App\Models\SubmissionRiskLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubmissionRiskLog>
 */
class SubmissionRiskLogFactory extends Factory
{
    protected $model = SubmissionRiskLog::class;

    public function definition(): array
    {
        return [
            'candidate_submission_id' => CandidateSubmission::factory(),
            'risk_factor' => 'HIGH_VELOCITY_IP',
            'risk_weight' => 25,
            'details' => ['submitted_count_in_window' => 12],
            'created_at' => now(),
        ];
    }
}

<?php

namespace App\Services;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\PredictionModel;
use App\Models\PredictionResult;
use App\Models\SubmissionRiskLog;
use Illuminate\Support\Str;

class PredictionService
{
    /**
     * Process candidate submission, anti-abuse checks, raw score calculation, and rank prediction.
     *
     * @param  array{
     *     prediction_model_id: int,
     *     exam_stage_id: int,
     *     shift_id: ?int,
     *     category_id: ?int,
     *     name: string,
     *     gender: string,
     *     total_questions: int,
     *     correct_answers: int,
     *     incorrect_answers: int,
     *     ip_address: ?string,
     *     user_agent: ?string,
     *     session_token: ?string,
     *     device_fingerprint: ?string
     * }  $data
     */
    public function predict(array $data): PredictionResult
    {
        $model = PredictionModel::findOrFail($data['prediction_model_id']);

        // 1. Calculate Score
        $totalQuestions = (int) $data['total_questions'];
        $correct = (int) $data['correct_answers'];
        $incorrect = (int) $data['incorrect_answers'];

        $formulaConfig = $model->formula_config ?? [];
        $totalMarks = (float) $model->total_marks;

        $marksPerQuestion = isset($formulaConfig['marks_per_question'])
            ? (float) $formulaConfig['marks_per_question']
            : ($totalQuestions > 0 ? $totalMarks / $totalQuestions : 1.0);

        $negativeMarks = isset($formulaConfig['negative_marks_per_question'])
            ? (float) $formulaConfig['negative_marks_per_question']
            : ($marksPerQuestion * ((float) ($model->negative_marking_ratio ?? 0.25)));

        $rawScore = round(($correct * $marksPerQuestion) - ($incorrect * $negativeMarks), 2);

        // 2. Anti-Abuse Risk Evaluation
        $ipHash = hash('sha256', $data['ip_address'] ?? '127.0.0.1');
        $uaHash = hash('sha256', $data['user_agent'] ?? 'unknown');
        $sessionToken = $data['session_token'] ?? Str::uuid()->toString();
        $deviceFingerprint = $data['device_fingerprint'] ?? hash('sha256', ($data['ip_address'] ?? '').($data['user_agent'] ?? ''));

        $recentSubmissionsCount = CandidateSubmission::where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        $riskScore = 0;
        $trustStatus = SubmissionTrustStatus::TRUSTED;
        $riskFactors = [];

        if ($recentSubmissionsCount >= 5) {
            $riskScore += 50;
            $trustStatus = SubmissionTrustStatus::SUSPICIOUS;
            $riskFactors[] = ['factor' => 'HIGH_VELOCITY_IP', 'weight' => 50, 'details' => ['recent_count' => $recentSubmissionsCount]];
        }

        if ($correct + $incorrect > $totalQuestions) {
            $riskScore += 50;
            $trustStatus = SubmissionTrustStatus::REJECTED;
            $riskFactors[] = ['factor' => 'INVALID_ANSWER_COUNT', 'weight' => 50, 'details' => ['attempted' => $correct + $incorrect, 'total' => $totalQuestions]];
        }

        // 3. Create Submission Record
        $submission = CandidateSubmission::create([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $data['exam_stage_id'],
            'shift_id' => $data['shift_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'candidate_identifier' => trim($data['name']),
            'total_attempted' => $correct + $incorrect,
            'correct_answers' => $correct,
            'incorrect_answers' => $incorrect,
            'raw_score' => $rawScore,
            'normalized_score' => $rawScore,
            'session_token' => $sessionToken,
            'ip_hash' => $ipHash,
            'user_agent_hash' => $uaHash,
            'device_fingerprint' => $deviceFingerprint,
            'risk_score' => $riskScore,
            'trust_status' => $trustStatus,
            'submitted_at' => now(),
        ]);

        // Record risk logs if any
        foreach ($riskFactors as $rf) {
            SubmissionRiskLog::create([
                'candidate_submission_id' => $submission->id,
                'risk_factor' => $rf['factor'],
                'risk_weight' => $rf['weight'],
                'details' => $rf['details'],
                'created_at' => now(),
            ]);
        }

        // 4. Calculate Ranks against Trusted Dataset for the Exam Stage
        $trustedBaseQuery = CandidateSubmission::where('exam_stage_id', $data['exam_stage_id'])
            ->where('trust_status', SubmissionTrustStatus::TRUSTED);

        $totalTrustedCount = (clone $trustedBaseQuery)->count();

        // Overall Rank (number of higher scores + 1)
        $rankOverall = (clone $trustedBaseQuery)->where('raw_score', '>', $rawScore)->count() + 1;

        // Category Rank
        $rankCategory = null;
        if (! empty($data['category_id'])) {
            $rankCategory = (clone $trustedBaseQuery)
                ->where('category_id', $data['category_id'])
                ->where('raw_score', '>', $rawScore)
                ->count() + 1;
        }

        // Gender Rank (portable Laravel JSON syntax)
        $gender = in_array(strtolower($data['gender']), ['male', 'female', 'other']) ? ucfirst(strtolower($data['gender'])) : 'Other';

        $rankGender = PredictionResult::whereHas('candidateSubmission', function ($q) use ($data) {
            $q->where('exam_stage_id', $data['exam_stage_id'])
                ->where('trust_status', SubmissionTrustStatus::TRUSTED);
        })
            ->where('metadata->gender', $gender)
            ->whereHas('candidateSubmission', function ($q) use ($rawScore) {
                $q->where('raw_score', '>', $rawScore);
            })
            ->count() + 1;

        // Percentile calculation
        $percentile = 100.0;
        if ($totalTrustedCount > 1) {
            $percentile = round((($totalTrustedCount - $rankOverall + 1) / $totalTrustedCount) * 100, 2);
            $percentile = min(99.99, max(0.01, $percentile));
        }

        // 5. Store and return Prediction Result
        return PredictionResult::create([
            'candidate_submission_id' => $submission->id,
            'predicted_rank_overall' => $rankOverall,
            'predicted_rank_category' => $rankCategory,
            'percentile' => $percentile,
            'confidence_score' => 95.00,
            'metadata' => [
                'candidate_name' => trim($data['name']),
                'gender' => $gender,
                'predicted_rank_gender' => $rankGender,
                'total_crowd_samples' => $totalTrustedCount,
                'marks_per_question' => $marksPerQuestion,
                'negative_marks_per_question' => $negativeMarks,
            ],
            'calculated_at' => now(),
        ]);
    }
}

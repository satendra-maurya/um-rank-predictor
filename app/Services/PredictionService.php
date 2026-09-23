<?php

namespace App\Services;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\PredictionModel;
use App\Models\PredictionResult;
use App\Models\SubmissionRiskLog;
use Illuminate\Support\Facades\DB;
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

        // 1. Determine Score (Raw score provided directly from candidate form)
        $rawScore = isset($data['raw_score'])
            ? round((float) $data['raw_score'], 2)
            : round(((int) ($data['correct_answers'] ?? 0) * 2.0) - ((int) ($data['incorrect_answers'] ?? 0) * 0.5), 2);

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

        $candidateName = trim($data['candidate_name'] ?? $data['name'] ?? '');
        $candidateIdentifier = trim($data['candidate_identifier'] ?? $data['roll_number'] ?? '');
        $dob = ! empty($data['dob']) ? $data['dob'] : null;

        // 3. Create Submission & Record Consent in Transaction
        $submission = DB::transaction(function () use ($model, $data, $rawScore, $ipHash, $uaHash, $sessionToken, $deviceFingerprint, $riskScore, $trustStatus, $riskFactors, $candidateName, $candidateIdentifier, $dob) {
            // Record Purpose Consent for Rank Prediction
            app(ConsentService::class)->give('rank_prediction', [
                'user_id' => auth()->id(),
                'session_token' => $sessionToken,
                'ip_address' => $data['ip_address'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
                'source' => 'rank_predictor_submission',
                'metadata' => [
                    'exam_stage_id' => $data['exam_stage_id'],
                ],
            ]);

            $sub = CandidateSubmission::create([
                'prediction_model_id' => $model->id,
                'exam_stage_id' => $data['exam_stage_id'],
                'shift_id' => $data['shift_id'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'candidate_name' => $candidateName ?: null,
                'candidate_identifier' => $candidateIdentifier,
                'dob' => $dob,
                'total_attempted' => isset($data['total_attempted']) ? (int) $data['total_attempted'] : null,
                'correct_answers' => isset($data['correct_answers']) ? (int) $data['correct_answers'] : null,
                'incorrect_answers' => isset($data['incorrect_answers']) ? (int) $data['incorrect_answers'] : null,
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

            foreach ($riskFactors as $rf) {
                SubmissionRiskLog::create([
                    'candidate_submission_id' => $sub->id,
                    'risk_factor' => $rf['factor'],
                    'risk_weight' => $rf['weight'],
                    'details' => $rf['details'],
                    'created_at' => now(),
                ]);
            }

            return $sub;
        });

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
                'candidate_name' => $candidateName ?: $candidateIdentifier,
                'gender' => $gender,
                'predicted_rank_gender' => $rankGender,
                'total_crowd_samples' => $totalTrustedCount,
            ],
            'calculated_at' => now(),
        ]);
    }
}

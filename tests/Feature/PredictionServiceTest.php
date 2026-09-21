<?php

namespace Tests\Feature;

use App\Enums\SubmissionTrustStatus;
use App\Models\Category;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_prediction_service_calculates_raw_score_and_ranks(): void
    {
        $stage = ExamStage::factory()->create();
        $model = PredictionModel::factory()->create([
            'exam_stage_id' => $stage->id,
            'total_marks' => 200.00,
            'negative_marking_ratio' => 0.25,
            'formula_config' => ['marks_per_question' => 2.0, 'negative_marks_per_question' => 0.50],
        ]);
        $category = Category::factory()->create(['code' => 'OBC']);

        $service = new PredictionService;

        // High score submission
        $result1 = $service->predict([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $stage->id,
            'shift_id' => null,
            'category_id' => $category->id,
            'name' => 'Top Candidate',
            'gender' => 'Male',
            'total_questions' => 100,
            'correct_answers' => 80, // 80 * 2 = 160
            'incorrect_answers' => 10, // 10 * 0.5 = 5 -> Raw score = 155.0
            'ip_address' => '192.168.1.10',
            'user_agent' => 'Mozilla',
            'session_token' => 'token-1',
            'device_fingerprint' => 'fp-1',
        ]);

        $this->assertEquals(155.0, $result1->candidateSubmission->raw_score);
        $this->assertEquals(1, $result1->predicted_rank_overall);
        $this->assertEquals(1, $result1->predicted_rank_category);

        // Second lower score submission
        $result2 = $service->predict([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $stage->id,
            'shift_id' => null,
            'category_id' => $category->id,
            'name' => 'Second Candidate',
            'gender' => 'Male',
            'total_questions' => 100,
            'correct_answers' => 60, // 60 * 2 = 120
            'incorrect_answers' => 20, // 20 * 0.5 = 10 -> Raw score = 110.0
            'ip_address' => '192.168.1.11',
            'user_agent' => 'Mozilla',
            'session_token' => 'token-2',
            'device_fingerprint' => 'fp-2',
        ]);

        $this->assertEquals(110.0, $result2->candidateSubmission->raw_score);
        $this->assertEquals(2, $result2->predicted_rank_overall);
        $this->assertEquals(2, $result2->predicted_rank_category);
        $this->assertEquals(50.0, $result2->percentile);
    }

    public function test_prediction_service_anti_abuse_flags_rapid_repeat_submissions(): void
    {
        $stage = ExamStage::factory()->create();
        $model = PredictionModel::factory()->create(['exam_stage_id' => $stage->id]);
        $category = Category::factory()->create();

        $service = new PredictionService;

        // Submit 5 times rapidly from same IP
        for ($i = 1; $i <= 5; $i++) {
            $service->predict([
                'prediction_model_id' => $model->id,
                'exam_stage_id' => $stage->id,
                'shift_id' => null,
                'category_id' => $category->id,
                'name' => 'Repeated Bot '.$i,
                'gender' => 'Male',
                'total_questions' => 100,
                'correct_answers' => 50,
                'incorrect_answers' => 50,
                'ip_address' => '10.0.0.99',
                'user_agent' => 'BotAgent',
                'session_token' => 'repeat-token-'.$i,
                'device_fingerprint' => 'bot-fp',
            ]);
        }

        // 6th submission should trigger risk score and SUSPICIOUS trust status
        $suspiciousResult = $service->predict([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $stage->id,
            'shift_id' => null,
            'category_id' => $category->id,
            'name' => 'Repeated Bot 6',
            'gender' => 'Male',
            'total_questions' => 100,
            'correct_answers' => 50,
            'incorrect_answers' => 50,
            'ip_address' => '10.0.0.99',
            'user_agent' => 'BotAgent',
            'session_token' => 'repeat-token-6',
            'device_fingerprint' => 'bot-fp',
        ]);

        $this->assertEquals(SubmissionTrustStatus::SUSPICIOUS, $suspiciousResult->candidateSubmission->trust_status);
        $this->assertGreaterThan(0, $suspiciousResult->candidateSubmission->risk_score);
        $this->assertCount(1, $suspiciousResult->candidateSubmission->riskLogs);
    }
}

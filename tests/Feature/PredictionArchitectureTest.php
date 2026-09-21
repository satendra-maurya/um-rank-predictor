<?php

namespace Tests\Feature;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\Category;
use App\Models\Cutoff;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use App\Models\PredictionResult;
use App\Models\Shift;
use App\Models\SubmissionRiskLog;
use App\Models\Vacancy;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_prediction_model_version_uniqueness_per_stage(): void
    {
        $stage = ExamStage::factory()->create();

        $modelV1 = PredictionModel::factory()->create([
            'exam_stage_id' => $stage->id,
            'version' => 'v1.0',
        ]);

        $this->assertEquals('v1.0', $modelV1->version);
        $this->assertEquals($stage->id, $modelV1->examStage->id);

        $this->expectException(UniqueConstraintViolationException::class);
        PredictionModel::factory()->create([
            'exam_stage_id' => $stage->id,
            'version' => 'v1.0',
        ]);
    }

    public function test_candidate_submission_and_prediction_results_flow(): void
    {
        $stage = ExamStage::factory()->create();
        $model = PredictionModel::factory()->create(['exam_stage_id' => $stage->id]);
        $shift = Shift::factory()->create(['exam_stage_id' => $stage->id]);
        $category = Category::factory()->create(['code' => 'OBC']);

        $submission = CandidateSubmission::factory()->create([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $stage->id,
            'shift_id' => $shift->id,
            'category_id' => $category->id,
            'raw_score' => 145.50,
            'trust_status' => SubmissionTrustStatus::TRUSTED,
            'risk_score' => 0,
            'ip_hash' => hash('sha256', '192.168.1.1'),
        ]);

        $result = PredictionResult::factory()->create([
            'candidate_submission_id' => $submission->id,
            'predicted_rank_overall' => 24,
            'predicted_rank_category' => 5,
            'percentile' => 99.85,
        ]);

        $this->assertEquals(145.50, $submission->raw_score);
        $this->assertEquals(SubmissionTrustStatus::TRUSTED, $submission->trust_status);
        $this->assertEquals(24, $submission->predictionResult->predicted_rank_overall);
        $this->assertEquals(5, $submission->predictionResult->predicted_rank_category);
        $this->assertEquals(99.85, $submission->predictionResult->percentile);
        $this->assertEquals($submission->id, $result->candidateSubmission->id);
    }

    public function test_anti_abuse_risk_logging_for_suspicious_submission(): void
    {
        $submission = CandidateSubmission::factory()->create([
            'trust_status' => SubmissionTrustStatus::SUSPICIOUS,
            'risk_score' => 75,
        ]);

        $log1 = SubmissionRiskLog::factory()->create([
            'candidate_submission_id' => $submission->id,
            'risk_factor' => 'DUPLICATE_ROLL_NUMBER',
            'risk_weight' => 50,
        ]);

        $log2 = SubmissionRiskLog::factory()->create([
            'candidate_submission_id' => $submission->id,
            'risk_factor' => 'HIGH_VELOCITY_IP',
            'risk_weight' => 25,
        ]);

        $this->assertCount(2, $submission->riskLogs);
        $this->assertEquals(75, $submission->risk_score);
        $this->assertEquals(SubmissionTrustStatus::SUSPICIOUS, $submission->trust_status);
    }

    public function test_vacancies_and_cutoffs_reference_data(): void
    {
        $stage = ExamStage::factory()->create();
        $category = Category::factory()->create(['code' => 'UR']);

        $cutoff = Cutoff::factory()->create([
            'exam_stage_id' => $stage->id,
            'category_id' => $category->id,
            'post_name' => 'Assistant Section Officer',
            'cutoff_marks' => 152.25,
            'cutoff_rank' => 450,
        ]);

        $vacancy = Vacancy::factory()->create([
            'exam_cycle_id' => $stage->exam_cycle_id,
            'category_id' => $category->id,
            'post_name' => 'Assistant Section Officer',
            'total_vacancies' => 120,
        ]);

        $this->assertEquals(152.25, $cutoff->cutoff_marks);
        $this->assertEquals(450, $cutoff->cutoff_rank);
        $this->assertEquals(120, $vacancy->total_vacancies);
    }
}

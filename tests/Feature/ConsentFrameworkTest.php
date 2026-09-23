<?php

namespace Tests\Feature;

use App\Enums\ConsentStatus;
use App\Livewire\RankPredictor;
use App\Models\Category;
use App\Models\ConsentPurpose;
use App\Models\ConsentRecord;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use App\Services\ConsentService;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConsentFrameworkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default consent purpose
        ConsentPurpose::firstOrCreate(
            ['key' => 'rank_prediction'],
            [
                'name' => 'Rank Prediction Service',
                'description' => 'Processing candidate data for providing the rank prediction service.',
                'version' => '1.0',
                'status' => 1,
            ]
        );
    }

    public function test_candidate_cannot_submit_without_required_consent(): void
    {
        $category = Category::factory()->create();
        $stage = ExamStage::factory()->create();

        Livewire::test(RankPredictor::class)
            ->set('step', 5)
            ->set('selectedStageId', $stage->id)
            ->set('name', 'Rahul Kumar')
            ->set('roll_number', 'ROLL123456')
            ->set('dob', '2000-01-15')
            ->set('raw_score', 145.50)
            ->set('category_id', $category->id)
            ->set('gender', 'Male')
            ->set('consent', false)
            ->call('submitPrediction')
            ->assertHasErrors(['consent' => 'accepted']);
    }

    public function test_candidate_can_submit_with_consent(): void
    {
        $category = Category::factory()->create();
        $stage = ExamStage::factory()->create();

        Livewire::test(RankPredictor::class)
            ->set('step', 5)
            ->set('selectedStageId', $stage->id)
            ->set('name', 'Rahul Kumar')
            ->set('roll_number', 'ROLL123456')
            ->set('dob', '2000-01-15')
            ->set('raw_score', 145.50)
            ->set('category_id', $category->id)
            ->set('gender', 'Male')
            ->set('consent', true)
            ->call('submitPrediction')
            ->assertHasNoErrors()
            ->assertSet('step', 6);
    }

    public function test_consent_record_is_created_with_all_required_attributes(): void
    {
        $stage = ExamStage::factory()->create();
        $category = Category::factory()->create();

        $service = app(PredictionService::class);
        $result = $service->predict([
            'prediction_model_id' => PredictionModel::firstOrCreate(
                ['exam_stage_id' => $stage->id, 'version' => 'v1.0'],
                ['name' => 'Test Model', 'total_marks' => 200.0, 'negative_marking_ratio' => 0.25, 'status' => 1]
            )->id,
            'exam_stage_id' => $stage->id,
            'category_id' => $category->id,
            'candidate_identifier' => 'ROLL999',
            'dob' => '1999-05-20',
            'gender' => 'Male',
            'raw_score' => 120.0,
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 TestBrowser',
            'session_token' => 'test-session-123',
        ]);

        $record = ConsentRecord::latest()->first();

        $this->assertNotNull($record);
        $this->assertEquals('rank_prediction', $record->purpose->key);
        $this->assertEquals(ConsentStatus::GRANTED, $record->consent_status);
        $this->assertNotNull($record->consented_at);
        $this->assertEquals('1.0', $record->consent_version);
        $this->assertEquals('1.0', $record->notice_version);
        $this->assertEquals('1.0', $record->privacy_policy_version);
        $this->assertEquals('rank_predictor_submission', $record->source);
        $this->assertEquals(hash('sha256', '192.168.1.1'), $record->ip_hash);
        $this->assertEquals(hash('sha256', 'Mozilla/5.0 TestBrowser'), $record->user_agent_hash);
    }

    public function test_guest_candidate_works_without_user_id(): void
    {
        $consentService = app(ConsentService::class);

        $record = $consentService->give('rank_prediction', [
            'session_token' => 'guest-session-456',
            'ip_address' => '10.0.0.1',
            'user_agent' => 'GuestBrowser',
        ]);

        $this->assertNull($record->user_id);
        $this->assertEquals('guest-session-456', $record->session_token);
        $this->assertEquals(ConsentStatus::GRANTED, $record->consent_status);
    }

    public function test_existing_candidate_fields_and_null_fields_stored_correctly(): void
    {
        $stage = ExamStage::factory()->create();
        $category = Category::factory()->create();

        $service = app(PredictionService::class);
        $result = $service->predict([
            'prediction_model_id' => PredictionModel::firstOrCreate(
                ['exam_stage_id' => $stage->id, 'version' => 'v1.0'],
                ['name' => 'Test Model', 'total_marks' => 200.0, 'negative_marking_ratio' => 0.25, 'status' => 1]
            )->id,
            'exam_stage_id' => $stage->id,
            'category_id' => $category->id,
            'candidate_identifier' => 'ROLL-TEST-100',
            'dob' => '2001-12-31',
            'gender' => 'Female',
            'raw_score' => 175.25,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'session_token' => 'sess-100',
        ]);

        $submission = $result->candidateSubmission;

        $this->assertEquals('ROLL-TEST-100', $submission->candidate_identifier);
        $this->assertEquals('2001-12-31', $submission->dob->format('Y-m-d'));
        $this->assertEquals(175.25, $submission->raw_score);
        $this->assertNull($submission->total_attempted);
        $this->assertNull($submission->correct_answers);
        $this->assertNull($submission->incorrect_answers);
    }

    public function test_consent_withdrawal_creates_withdrawn_state_without_corrupting_history(): void
    {
        $consentService = app(ConsentService::class);
        $sessionToken = 'session-history-test';

        // 1. Give Consent
        $grantedRecord = $consentService->give('rank_prediction', [
            'session_token' => $sessionToken,
            'source' => 'web_form',
        ]);

        $this->assertEquals(ConsentStatus::GRANTED, $grantedRecord->consent_status);
        $this->assertTrue($consentService->hasConsent('rank_prediction', ['session_token' => $sessionToken]));

        // 2. Withdraw Consent
        $withdrawnRecord = $consentService->withdraw('rank_prediction', [
            'session_token' => $sessionToken,
            'source' => 'privacy_settings',
        ]);

        $this->assertEquals(ConsentStatus::WITHDRAWN, $withdrawnRecord->consent_status);
        $this->assertNotNull($withdrawnRecord->withdrawn_at);
        $this->assertFalse($consentService->hasConsent('rank_prediction', ['session_token' => $sessionToken]));

        // 3. Historical verification: both records exist in DB
        $allRecords = ConsentRecord::where('session_token', $sessionToken)->get();
        $this->assertCount(2, $allRecords);
        $this->assertEquals(ConsentStatus::GRANTED, $allRecords->first()->consent_status);
        $this->assertEquals(ConsentStatus::WITHDRAWN, $allRecords->last()->consent_status);
    }
}

<?php

namespace Tests\Unit;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\Category;
use App\Models\ExamStage;
use App\Services\RankingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RankingService $rankingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rankingService = new RankingService;
    }

    /** TEST 1: Different scores (100, 90, 80 => Ranks: 1, 2, 3) */
    public function test_1_different_scores_yields_correct_ranks(): void
    {
        $stage = ExamStage::factory()->create();

        $sub1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 100.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $sub2 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 90.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $sub3 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 80.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $res1 = $this->rankingService->calculateForSubmission($sub1);
        $res2 = $this->rankingService->calculateForSubmission($sub2);
        $res3 = $this->rankingService->calculateForSubmission($sub3);

        $this->assertEquals(1, $res1['overall_rank']);
        $this->assertEquals(2, $res2['overall_rank']);
        $this->assertEquals(3, $res3['overall_rank']);
        $this->assertEquals(3, $res1['total_candidates']);
    }

    /** TEST 2: Same score + older candidate wins (OLDER_HIGHER) */
    public function test_2_same_score_older_candidate_wins(): void
    {
        $stage = ExamStage::factory()->create([
            'ranking_config' => [
                'enabled' => true,
                'score_field' => 'raw_score',
                'tie_breakers' => [
                    ['field' => 'dob', 'direction' => 'asc', 'rule' => 'OLDER_HIGHER'],
                    ['field' => 'id', 'direction' => 'asc'],
                ],
            ],
        ]);

        $subA = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1995-01-01',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $subB = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1998-01-01',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $resA = $this->rankingService->calculateForSubmission($subA);
        $resB = $this->rankingService->calculateForSubmission($subB);

        $this->assertEquals(1, $resA['overall_rank']);
        $this->assertEquals(2, $resB['overall_rank']);
    }

    /** TEST 3: Same score + younger candidate wins (YOUNGER_HIGHER) */
    public function test_3_same_score_younger_candidate_wins(): void
    {
        $stage = ExamStage::factory()->create([
            'ranking_config' => [
                'enabled' => true,
                'score_field' => 'raw_score',
                'tie_breakers' => [
                    ['field' => 'dob', 'direction' => 'desc', 'rule' => 'YOUNGER_HIGHER'],
                    ['field' => 'id', 'direction' => 'asc'],
                ],
            ],
        ]);

        $subA = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1995-01-01',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $subB = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1998-01-01',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $resA = $this->rankingService->calculateForSubmission($subA);
        $resB = $this->rankingService->calculateForSubmission($subB);

        $this->assertEquals(2, $resA['overall_rank']);
        $this->assertEquals(1, $resB['overall_rank']);
    }

    /** TEST 4: Same score + same DOB + name tie-break (candidate_name ASC) */
    public function test_4_name_tie_break(): void
    {
        $stage = ExamStage::factory()->create([
            'ranking_config' => [
                'enabled' => true,
                'score_field' => 'raw_score',
                'tie_breakers' => [
                    ['field' => 'dob', 'direction' => 'asc', 'rule' => 'OLDER_HIGHER'],
                    ['field' => 'candidate_name', 'direction' => 'asc'],
                    ['field' => 'id', 'direction' => 'asc'],
                ],
            ],
        ]);

        $subA = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1995-01-01',
            'candidate_name' => 'Amit Sharma',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $subB = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 82.50,
            'dob' => '1995-01-01',
            'candidate_name' => 'Vikram Singh',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $resA = $this->rankingService->calculateForSubmission($subA);
        $resB = $this->rankingService->calculateForSubmission($subB);

        $this->assertEquals(1, $resA['overall_rank']);
        $this->assertEquals(2, $resB['overall_rank']);
    }

    /** TEST 5: Complete tie -> Deterministic ID ordering */
    public function test_5_complete_tie_deterministic_id_fallback(): void
    {
        $stage = ExamStage::factory()->create();

        $subFirst = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 80.0,
            'dob' => '1995-01-01',
            'candidate_name' => 'Same Name',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $subSecond = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 80.0,
            'dob' => '1995-01-01',
            'candidate_name' => 'Same Name',
            'trust_status' => SubmissionTrustStatus::TRUSTED,
        ]);

        $resFirst = $this->rankingService->calculateForSubmission($subFirst);
        $resSecond = $this->rankingService->calculateForSubmission($subSecond);

        $this->assertEquals(1, $resFirst['overall_rank']);
        $this->assertEquals(2, $resSecond['overall_rank']);
    }

    /** TEST 6: Category rank (isolated by category_id) */
    public function test_6_category_rank_does_not_mix_categories(): void
    {
        $stage = ExamStage::factory()->create();

        $catGen = Category::factory()->create(['name' => 'General', 'code' => 'GEN']);
        $catObc = Category::factory()->create(['name' => 'OBC', 'code' => 'OBC']);

        $gen1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'category_id' => $catGen->id, 'raw_score' => 90.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $obc1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'category_id' => $catObc->id, 'raw_score' => 85.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $obc2 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'category_id' => $catObc->id, 'raw_score' => 95.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $resGen1 = $this->rankingService->calculateForSubmission($gen1);
        $resObc1 = $this->rankingService->calculateForSubmission($obc1);
        $resObc2 = $this->rankingService->calculateForSubmission($obc2);

        $this->assertEquals(1, $resGen1['category_rank']);
        $this->assertEquals(2, $resObc1['category_rank']);
        $this->assertEquals(1, $resObc2['category_rank']);
    }

    /** TEST 7: Gender rank (isolated by gender) */
    public function test_7_gender_rank_does_not_mix_genders(): void
    {
        $stage = ExamStage::factory()->create();

        $male1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'gender' => 'Male', 'raw_score' => 90.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $female1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'gender' => 'Female', 'raw_score' => 85.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $female2 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'gender' => 'Female', 'raw_score' => 95.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $resM1 = $this->rankingService->calculateForSubmission($male1);
        $resF1 = $this->rankingService->calculateForSubmission($female1);
        $resF2 = $this->rankingService->calculateForSubmission($female2);

        $this->assertEquals(1, $resM1['gender_rank']);
        $this->assertEquals(2, $resF1['gender_rank']);
        $this->assertEquals(1, $resF2['gender_rank']);
    }

    /** TEST 8: Untrusted candidate does not affect trusted candidate rank */
    public function test_8_untrusted_candidate_does_not_affect_trusted_rank(): void
    {
        $stage = ExamStage::factory()->create();

        $suspicious = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 100.0, 'trust_status' => SubmissionTrustStatus::SUSPICIOUS]);
        $trusted = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 90.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $resTrusted = $this->rankingService->calculateForSubmission($trusted);

        $this->assertEquals(1, $resTrusted['overall_rank']);
        $this->assertEquals(1, $resTrusted['total_candidates']);
    }

    /** TEST 9: Different exam stage does not affect rank */
    public function test_9_different_exam_stage_does_not_affect_rank(): void
    {
        $stageA = ExamStage::factory()->create();
        $stageB = ExamStage::factory()->create();

        $subA = CandidateSubmission::factory()->create(['exam_stage_id' => $stageA->id, 'raw_score' => 80.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $subB = CandidateSubmission::factory()->create(['exam_stage_id' => $stageB->id, 'raw_score' => 100.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $resA = $this->rankingService->calculateForSubmission($subA);

        $this->assertEquals(1, $resA['overall_rank']);
        $this->assertEquals(1, $resA['total_candidates']);
    }

    /** TEST 10: NULL category yields NULL category_rank */
    public function test_10_null_category_yields_null_category_rank(): void
    {
        $stage = ExamStage::factory()->create();

        $sub = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'category_id' => null, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $res = $this->rankingService->calculateForSubmission($sub);

        $this->assertNull($res['category_rank']);
    }

    /** TEST 11: Single trusted candidate yields rank 1 and clamped percentile 99.99 */
    public function test_11_single_trusted_candidate_percentile(): void
    {
        $stage = ExamStage::factory()->create();

        $sub = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 75.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $res = $this->rankingService->calculateForSubmission($sub);

        $this->assertEquals(1, $res['overall_rank']);
        $this->assertEquals(99.99, $res['percentile']);
        $this->assertEquals(1, $res['total_candidates']);
    }

    /** TEST 12: Trust status determines if submission enters ranking dataset */
    public function test_12_trust_status_determines_dataset_entry(): void
    {
        $stage = ExamStage::factory()->create();

        $untrustedSub = CandidateSubmission::factory()->create([
            'exam_stage_id' => $stage->id,
            'raw_score' => 75.0,
            'trust_status' => SubmissionTrustStatus::SUSPICIOUS,
        ]);

        $res = $this->rankingService->calculateForSubmission($untrustedSub);

        $this->assertEquals(0, $res['total_candidates']);
        $this->assertNull($res['percentile']);
    }

    /** TEST 13: Score field raw_score configured */
    public function test_13_score_field_raw_score_configured(): void
    {
        $stage = ExamStage::factory()->create([
            'ranking_config' => [
                'enabled' => true,
                'score_field' => 'raw_score',
            ],
        ]);

        $sub1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 95.0, 'normalized_score' => 50.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $sub2 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 85.0, 'normalized_score' => 100.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $res1 = $this->rankingService->calculateForSubmission($sub1);
        $res2 = $this->rankingService->calculateForSubmission($sub2);

        $this->assertEquals(1, $res1['overall_rank']);
        $this->assertEquals(2, $res2['overall_rank']);
    }

    /** TEST 14: Score field normalized_score configured */
    public function test_14_score_field_normalized_score_configured(): void
    {
        $stage = ExamStage::factory()->create([
            'ranking_config' => [
                'enabled' => true,
                'score_field' => 'normalized_score',
            ],
        ]);

        $sub1 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 95.0, 'normalized_score' => 50.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);
        $sub2 = CandidateSubmission::factory()->create(['exam_stage_id' => $stage->id, 'raw_score' => 85.0, 'normalized_score' => 100.0, 'trust_status' => SubmissionTrustStatus::TRUSTED]);

        $res1 = $this->rankingService->calculateForSubmission($sub1);
        $res2 = $this->rankingService->calculateForSubmission($sub2);

        $this->assertEquals(2, $res1['overall_rank']);
        $this->assertEquals(1, $res2['overall_rank']);
    }
}

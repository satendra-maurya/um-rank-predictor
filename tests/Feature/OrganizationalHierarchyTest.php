<?php

namespace Tests\Feature;

use App\Enums\ExamAuthorityLevel;
use App\Enums\ExamCycleStatus;
use App\Models\Exam;
use App\Models\ExamAuthority;
use App\Models\ExamCycle;
use App\Models\ExamStage;
use App\Models\ImportantLink;
use App\Models\Notice;
use App\Models\State;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationalHierarchyTest extends TestCase
{
    use RefreshDatabase;

    public function test_central_authority_has_null_state_id(): void
    {
        $ssc = ExamAuthority::factory()->create([
            'name' => 'Staff Selection Commission',
            'short_name' => 'SSC',
            'level' => ExamAuthorityLevel::CENTRAL,
            'state_id' => null,
        ]);

        $this->assertNull($ssc->state_id);
        $this->assertEquals(ExamAuthorityLevel::CENTRAL, $ssc->level);
        $this->assertNull($ssc->state);
    }

    public function test_state_authority_belongs_to_a_state(): void
    {
        $up = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'short_name' => 'UP',
            'code' => 'UP',
        ]);

        $upsssc = ExamAuthority::factory()->create([
            'name' => 'Uttar Pradesh Subordinate Services Selection Commission',
            'short_name' => 'UPSSSC',
            'level' => ExamAuthorityLevel::STATE,
            'state_id' => $up->id,
        ]);

        $this->assertNotNull($upsssc->state_id);
        $this->assertEquals('Uttar Pradesh', $upsssc->state->name);
        $this->assertCount(1, $up->examAuthorities);
    }

    public function test_exam_cycle_hierarchy_and_year_uniqueness(): void
    {
        $ssc = ExamAuthority::factory()->create(['short_name' => 'SSC']);
        $cgl = Exam::factory()->create([
            'exam_authority_id' => $ssc->id,
            'name' => 'Combined Graduate Level',
            'short_name' => 'CGL',
        ]);

        $cycle2026 = ExamCycle::factory()->create([
            'exam_id' => $cgl->id,
            'year' => 2026,
            'status' => ExamCycleStatus::ACTIVE,
        ]);

        $this->assertEquals('Combined Graduate Level', $cycle2026->exam->name);
        $this->assertEquals('SSC', $cycle2026->exam->examAuthority->short_name);

        $this->expectException(UniqueConstraintViolationException::class);
        ExamCycle::factory()->create([
            'exam_id' => $cgl->id,
            'year' => 2026,
        ]);
    }

    public function test_exam_stages_hierarchy_and_order_uniqueness(): void
    {
        $cycle = ExamCycle::factory()->create(['year' => 2026]);

        $tier1 = ExamStage::factory()->create([
            'exam_cycle_id' => $cycle->id,
            'name' => 'Tier 1',
            'stage_order' => 1,
        ]);

        $tier2 = ExamStage::factory()->create([
            'exam_cycle_id' => $cycle->id,
            'name' => 'Tier 2',
            'stage_order' => 2,
        ]);

        $this->assertCount(2, $cycle->examStages);
        $this->assertEquals(1, $tier1->stage_order);
        $this->assertEquals(2, $tier2->stage_order);

        $this->expectException(UniqueConstraintViolationException::class);
        ExamStage::factory()->create([
            'exam_cycle_id' => $cycle->id,
            'stage_order' => 1,
        ]);
    }

    public function test_notices_and_important_links_relationships(): void
    {
        $exam = Exam::factory()->create();
        $cycle = ExamCycle::factory()->create(['exam_id' => $exam->id]);
        $stage = ExamStage::factory()->create(['exam_cycle_id' => $cycle->id]);

        $notice = Notice::factory()->create([
            'exam_id' => $exam->id,
            'exam_cycle_id' => $cycle->id,
            'exam_stage_id' => $stage->id,
            'title' => 'Answer Key Released',
            'notice_type' => 'ANSWER_KEY',
        ]);

        $link = ImportantLink::factory()->create([
            'exam_id' => $exam->id,
            'exam_cycle_id' => $cycle->id,
            'exam_stage_id' => $stage->id,
            'title' => 'Download Admit Card',
            'url' => 'https://example.com/admit-card',
        ]);

        $this->assertEquals('Answer Key Released', $notice->title);
        $this->assertEquals($exam->id, $notice->exam->id);
        $this->assertEquals($cycle->id, $notice->examCycle->id);
        $this->assertEquals($stage->id, $notice->examStage->id);

        $this->assertEquals('Download Admit Card', $link->title);
        $this->assertEquals($stage->id, $link->examStage->id);
    }
}

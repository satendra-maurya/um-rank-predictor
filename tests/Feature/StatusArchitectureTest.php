<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Enums\ExamCycleStatus;
use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\ExamCycle;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StatusArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_cast_status_to_backed_enums(): void
    {
        $state = State::factory()->create(['status' => ActiveStatus::ACTIVE]);
        $this->assertInstanceOf(ActiveStatus::class, $state->status);
        $this->assertEquals(ActiveStatus::ACTIVE, $state->status);
        $this->assertEquals(1, $state->status->value);
        $this->assertDatabaseHas('states', [
            'id' => $state->id,
            'status' => 1,
        ]);

        $examCycle = ExamCycle::factory()->create(['status' => ExamCycleStatus::DRAFT]);
        $this->assertInstanceOf(ExamCycleStatus::class, $examCycle->status);
        $this->assertEquals(ExamCycleStatus::DRAFT, $examCycle->status);
        $this->assertEquals(0, $examCycle->status->value);
        $this->assertDatabaseHas('exam_cycles', [
            'id' => $examCycle->id,
            'status' => 0,
        ]);

        $submission = CandidateSubmission::factory()->create(['trust_status' => SubmissionTrustStatus::SUSPICIOUS]);
        $this->assertInstanceOf(SubmissionTrustStatus::class, $submission->trust_status);
        $this->assertEquals(SubmissionTrustStatus::SUSPICIOUS, $submission->trust_status);
        $this->assertEquals(2, $submission->trust_status->value);
        $this->assertDatabaseHas('candidate_submissions', [
            'id' => $submission->id,
            'trust_status' => 2,
        ]);
    }

    public function test_all_status_enums_have_labels(): void
    {
        $this->assertEquals('Active', ActiveStatus::ACTIVE->label());
        $this->assertEquals('Inactive', ActiveStatus::INACTIVE->label());

        $this->assertEquals('Draft', ExamCycleStatus::DRAFT->label());
        $this->assertEquals('Active', ExamCycleStatus::ACTIVE->label());
        $this->assertEquals('Completed', ExamCycleStatus::COMPLETED->label());
        $this->assertEquals('Cancelled', ExamCycleStatus::CANCELLED->label());

        $this->assertEquals('Trusted', SubmissionTrustStatus::TRUSTED->label());
        $this->assertEquals('Suspicious', SubmissionTrustStatus::SUSPICIOUS->label());
        $this->assertEquals('Rejected', SubmissionTrustStatus::REJECTED->label());
        $this->assertEquals('Pending Audit', SubmissionTrustStatus::PENDING_AUDIT->label());
    }

    public function test_database_seeder_populates_tinyint_status_values(): void
    {
        $this->seed(DatabaseSeeder::class);

        $stateCount = DB::table('states')->where('status', 1)->count();
        $this->assertGreaterThan(0, $stateCount);

        $authorityCount = DB::table('exam_authorities')->where('status', 1)->count();
        $this->assertGreaterThan(0, $authorityCount);

        $examCount = DB::table('exams')->where('status', 1)->count();
        $this->assertGreaterThan(0, $examCount);

        $cycleCount = DB::table('exam_cycles')->where('status', 1)->count();
        $this->assertGreaterThan(0, $cycleCount);

        $stageCount = DB::table('exam_stages')->where('status', 1)->count();
        $this->assertGreaterThan(0, $stageCount);

        $shiftCount = DB::table('shifts')->where('status', 1)->count();
        $this->assertGreaterThan(0, $shiftCount);

        $categoryCount = DB::table('categories')->where('status', 1)->count();
        $this->assertGreaterThan(0, $categoryCount);
    }
}

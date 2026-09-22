<?php

namespace Database\Seeders;

use App\Enums\ActiveStatus;
use App\Models\Exam;
use App\Models\ExamCycle;
use App\Models\ExamStage;
use Illuminate\Database\Seeder;

class ExamStageSeeder extends Seeder
{
    /**
     * Seed initial exam stages for rank prediction MVP.
     * Idempotent based on `[exam_cycle_id, stage_order]`.
     */
    public function run(): void
    {
        $stages = [
            [
                'exam_slug' => 'ssc-cgl',
                'year' => 2026,
                'name' => 'Tier 1',
                'slug' => 'tier-1',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'Computer Based Examination (Tier 1)',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-mts',
                'year' => 2026,
                'name' => 'Session 1',
                'slug' => 'session-1',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'Session 1 Examination (Numerical & Reasoning Ability)',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-chsl',
                'year' => 2026,
                'name' => 'Tier 1',
                'slug' => 'tier-1',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'Computer Based Examination (Tier 1)',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-cpo',
                'year' => 2026,
                'name' => 'Paper 1',
                'slug' => 'paper-1',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'Paper 1 Computer Based Examination',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'rrb-ntpc',
                'year' => 2026,
                'name' => 'CBT 1',
                'slug' => 'cbt-1',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'First Stage Computer Based Test (CBT 1)',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'rrb-group-d',
                'year' => 2026,
                'name' => 'CBT',
                'slug' => 'cbt',
                'stage_order' => 1,
                'type' => 'CBT',
                'description' => 'Computer Based Test (CBT)',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'upsssc-pet',
                'year' => 2026,
                'name' => 'Written Exam',
                'slug' => 'written-exam',
                'stage_order' => 1,
                'type' => 'OFFLINE_OMR',
                'description' => 'Preliminary Eligibility Test Written Examination',
                'status' => ActiveStatus::ACTIVE,
            ],
        ];

        foreach ($stages as $stageData) {
            $exam = Exam::where('slug', $stageData['exam_slug'])->first();

            if (! $exam) {
                continue;
            }

            $cycle = ExamCycle::where('exam_id', $exam->id)
                ->where('year', $stageData['year'])
                ->first();

            if (! $cycle) {
                continue;
            }

            ExamStage::updateOrCreate(
                [
                    'exam_cycle_id' => $cycle->id,
                    'stage_order' => $stageData['stage_order'],
                ],
                [
                    'name' => $stageData['name'],
                    'slug' => $stageData['slug'],
                    'type' => $stageData['type'],
                    'description' => $stageData['description'],
                    'status' => $stageData['status'],
                ]
            );
        }
    }
}

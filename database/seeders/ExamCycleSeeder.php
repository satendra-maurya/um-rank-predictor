<?php

namespace Database\Seeders;

use App\Enums\ExamCycleStatus;
use App\Models\Exam;
use App\Models\ExamCycle;
use Illuminate\Database\Seeder;

class ExamCycleSeeder extends Seeder
{
    /**
     * Seed initial 2026 recruitment cycles.
     * Idempotent based on `[exam_id, year]`.
     */
    public function run(): void
    {
        $cycles = [
            [
                'exam_slug' => 'ssc-cgl',
                'year' => 2026,
                'title' => 'SSC CGL Examination 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-mts',
                'year' => 2026,
                'title' => 'SSC MTS Examination 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-chsl',
                'year' => 2026,
                'title' => 'SSC CHSL Examination 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'ssc-cpo',
                'year' => 2026,
                'title' => 'SSC CPO Examination 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'rrb-ntpc',
                'year' => 2026,
                'title' => 'RRB NTPC Recruitment 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'rrb-group-d',
                'year' => 2026,
                'title' => 'RRB Group D Recruitment 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
            [
                'exam_slug' => 'upsssc-pet',
                'year' => 2026,
                'title' => 'UPSSSC PET 2026',
                'status' => ExamCycleStatus::ACTIVE,
            ],
        ];

        foreach ($cycles as $cycleData) {
            $exam = Exam::where('slug', $cycleData['exam_slug'])->first();

            if (! $exam) {
                continue;
            }

            ExamCycle::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'year' => $cycleData['year'],
                ],
                [
                    'title' => $cycleData['title'],
                    'status' => $cycleData['status'],
                ]
            );
        }
    }
}

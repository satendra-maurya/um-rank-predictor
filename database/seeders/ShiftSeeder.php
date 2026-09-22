<?php

namespace Database\Seeders;

use App\Enums\ActiveStatus;
use App\Models\ExamStage;
use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Seed initial generic shifts for active exam stages.
     * Idempotent based on `[exam_stage_id, name]`.
     */
    public function run(): void
    {
        $activeStages = ExamStage::where('status', ActiveStatus::ACTIVE)->get();
        $staticDate = '2026-10-21';

        $shiftsTemplate = [
            [
                'name' => 'Shift 1 (09:00 AM - 10:00 AM)',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'name' => 'Shift 2 (12:30 PM - 01:30 PM)',
                'start_time' => '12:30:00',
                'end_time' => '13:30:00',
                'status' => ActiveStatus::ACTIVE,
            ],
            [
                'name' => 'Shift 3 (04:00 PM - 05:00 PM)',
                'start_time' => '16:00:00',
                'end_time' => '17:00:00',
                'status' => ActiveStatus::ACTIVE,
            ],
        ];

        foreach ($activeStages as $stage) {
            foreach ($shiftsTemplate as $shiftData) {
                Shift::firstOrCreate(
                    [
                        'exam_stage_id' => $stage->id,
                        'name' => $shiftData['name'],
                    ],
                    [
                        'shift_date' => $staticDate,
                        'start_time' => $shiftData['start_time'],
                        'end_time' => $shiftData['end_time'],
                        'status' => $shiftData['status'],
                    ]
                );
            }
        }
    }
}

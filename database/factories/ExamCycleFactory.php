<?php

namespace Database\Factories;

use App\Enums\ExamCycleStatus;
use App\Models\Exam;
use App\Models\ExamCycle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamCycle>
 */
class ExamCycleFactory extends Factory
{
    protected $model = ExamCycle::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2025, 2030);

        return [
            'exam_id' => Exam::factory(),
            'year' => $year,
            'title' => 'Recruitment '.$year,
            'notification_date' => now()->subDays(30),
            'application_start_date' => now()->subDays(20),
            'application_end_date' => now()->addDays(10),
            'exam_start_date' => now()->addDays(30),
            'exam_end_date' => now()->addDays(35),
            'result_date' => now()->addDays(60),
            'status' => ExamCycleStatus::ACTIVE,
        ];
    }
}

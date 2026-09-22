<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\ExamStage;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shift>
 */
class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        return [
            'exam_stage_id' => ExamStage::factory(),
            'name' => 'Shift '.fake()->unique()->numberBetween(1, 100),
            'shift_date' => now()->addDays(5),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'status' => ActiveStatus::ACTIVE,
        ];
    }
}

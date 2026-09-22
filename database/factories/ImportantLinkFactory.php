<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Exam;
use App\Models\ImportantLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImportantLink>
 */
class ImportantLinkFactory extends Factory
{
    protected $model = ImportantLink::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'exam_cycle_id' => null,
            'exam_stage_id' => null,
            'title' => fake()->words(3, true),
            'url' => fake()->url(),
            'link_type' => 'APPLY_ONLINE',
            'is_external' => true,
            'sort_order' => 1,
            'status' => ActiveStatus::ACTIVE,
        ];
    }
}

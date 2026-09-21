<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Notice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Notice>
 */
class NoticeFactory extends Factory
{
    protected $model = Notice::class;

    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'exam_id' => Exam::factory(),
            'exam_cycle_id' => null,
            'exam_stage_id' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'notice_type' => 'NOTIFICATION',
            'notice_date' => now(),
            'official_url' => fake()->url(),
            'attachment_url' => fake()->url(),
            'content' => fake()->paragraph(),
            'is_important' => fake()->boolean(),
            'published_at' => now(),
            'status' => 'ACTIVE',
        ];
    }
}

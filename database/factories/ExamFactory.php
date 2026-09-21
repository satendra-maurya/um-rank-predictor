<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamAuthority;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $name = fake()->jobTitle().' Exam';

        return [
            'exam_authority_id' => ExamAuthority::factory(),
            'name' => $name,
            'short_name' => strtoupper(fake()->lexify('???')),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->sentence(),
            'exam_type' => 'GRADUATION_LEVEL',
            'status' => 'ACTIVE',
        ];
    }
}

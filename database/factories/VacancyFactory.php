<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ExamCycle;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vacancy>
 */
class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        return [
            'exam_cycle_id' => ExamCycle::factory(),
            'category_id' => Category::factory(),
            'post_name' => fake()->jobTitle(),
            'total_vacancies' => fake()->numberBetween(10, 500),
        ];
    }
}

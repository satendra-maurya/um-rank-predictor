<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Enums\ExamAuthorityLevel;
use App\Models\ExamAuthority;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ExamAuthority>
 */
class ExamAuthorityFactory extends Factory
{
    protected $model = ExamAuthority::class;

    public function definition(): array
    {
        $name = fake()->company().' Examination Board';

        return [
            'state_id' => null,
            'name' => $name,
            'short_name' => strtoupper(fake()->lexify('????')),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'level' => ExamAuthorityLevel::CENTRAL,
            'website_url' => fake()->url(),
            'logo' => null,
            'description' => fake()->sentence(),
            'status' => ActiveStatus::ACTIVE,
        ];
    }

    public function stateLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => ExamAuthorityLevel::STATE,
            'state_id' => State::factory(),
        ]);
    }
}

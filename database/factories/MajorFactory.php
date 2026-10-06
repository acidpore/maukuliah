<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Major>
 */
class MajorFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Teknik '.fake()->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['Teknik & Teknologi', 'Kesehatan', 'Sosial & Humaniora']),
            'description' => fake()->sentence(12),
            'courses' => [fake()->words(2, true), fake()->words(2, true)],
            'career_prospects' => [fake()->jobTitle()],
            'riasec_codes' => fake()->randomElement(['RIC', 'IRC', 'ASE', 'SIA', 'ECS', 'CEI']),
        ];
    }
}

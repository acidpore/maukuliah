<?php

namespace Database\Factories;

use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Scholarship>
 */
class ScholarshipFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Beasiswa '.fake()->unique()->words(2, true);

        return [
            'campus_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(14),
            'provider' => fake()->company(),
            'start_date' => today()->subDays(10),
            'end_date' => today()->addDays(30),
            'registration_url' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'start_date' => today()->subDays(60),
            'end_date' => today()->subDays(10),
        ]);
    }
}

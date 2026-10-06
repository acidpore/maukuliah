<?php

namespace Database\Factories;

use App\Enums\VerificationStatus;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Campus>
 */
class CampusFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Universitas '.fake()->unique()->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(Campus::TYPES),
            'form' => fake()->randomElement(array_keys(Campus::FORM_LABELS)),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'accreditation' => fake()->randomElement(['A', 'B', 'Unggul', 'Baik']),
            'description' => fake()->sentence(12),
            'established_year' => fake()->numberBetween(1950, 2020),
            'website' => null,
            'logo' => null,
            'verification_status' => VerificationStatus::Verified,
            'verified_at' => now(),
        ];
    }

    public function unverified(VerificationStatus $status = VerificationStatus::Draft): static
    {
        return $this->state(fn () => [
            'verification_status' => $status,
            'verified_at' => null,
        ]);
    }
}

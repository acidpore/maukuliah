<?php

namespace Database\Factories;

use App\Enums\BrochureType;
use App\Models\Brochure;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brochure>
 */
class BrochureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'campus_id' => Campus::factory(),
            'title' => 'Brosur '.fake()->words(2, true),
            'type' => BrochureType::General,
            'file_path' => 'brochures/'.fake()->slug().'.pdf',
        ];
    }
}

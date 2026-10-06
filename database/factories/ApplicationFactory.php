<?php

namespace Database\Factories;

use App\Enums\ClassSchedule;
use App\Enums\LastEducation;
use App\Enums\LeadStatus;
use App\Enums\ProgramType;
use App\Enums\SourceInfo;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'campus_id' => Campus::factory(),
            'major_id' => Major::factory(),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'whatsapp' => '081234567890',
            'last_education' => LastEducation::Sma,
            'region' => fake()->city(),
            'program_type' => ProgramType::Karyawan,
            'schedule' => ClassSchedule::Malam,
            'source_info' => SourceInfo::Website,
            'accepted_terms' => true,
            'status' => LeadStatus::New,
        ];
    }
}

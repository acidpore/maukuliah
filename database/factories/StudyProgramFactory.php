<?php

namespace Database\Factories;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Models\Campus;
use App\Models\Major;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    public function definition(): array
    {
        $monthly = fake()->numberBetween(4, 20) * 50000;

        return [
            'campus_id' => Campus::factory(),
            'major_id' => Major::factory(),
            'degree_level' => DegreeLevel::S1,
            'degree_title' => null,
            'program_type' => ProgramType::Reguler,
            'accreditation' => 'B',
            'registration_fee' => 100000,
            'first_payment' => $monthly * 2,
            'monthly_installment' => $monthly,
            'original_monthly_installment' => null,
            'schedules' => [ClassSchedule::Pagi],
            'methods' => [LearningMethod::TatapMuka],
        ];
    }
}

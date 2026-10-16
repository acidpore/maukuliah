<?php

namespace App\Http\Requests;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudyProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'major_id' => ['required', 'integer', 'exists:majors,id'],
            'degree_level' => ['required', Rule::enum(DegreeLevel::class)],
            'program_type' => ['required', Rule::enum(ProgramType::class)],
            'accreditation' => ['nullable', 'string', 'max:10'],
            'registration_fee' => ['required', 'integer', 'min:0'],
            'first_payment' => ['required', 'integer', 'min:0'],
            'monthly_installment' => ['required', 'integer', 'min:0'],
            'original_monthly_installment' => ['nullable', 'integer', 'gt:monthly_installment'],
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*' => [Rule::enum(ClassSchedule::class)],
            'methods' => ['required', 'array', 'min:1'],
            'methods.*' => [Rule::enum(LearningMethod::class)],
        ];
    }
}

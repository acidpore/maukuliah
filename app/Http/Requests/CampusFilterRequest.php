<?php

namespace App\Http\Requests;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Models\Campus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampusFilterRequest extends FormRequest
{
    public const MAX_TERM_LENGTH = 100;

    public const MAX_TEXT_LENGTH = 100;

    public const SORT_OPTIONS = ['name', 'newest', 'cheapest'];

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
            'q' => ['nullable', 'string', 'max:'.self::MAX_TERM_LENGTH],
            'sort' => ['nullable', Rule::in(self::SORT_OPTIONS)],
            'type' => ['nullable', Rule::in(Campus::TYPES)],
            'form' => ['nullable', Rule::in(array_keys(Campus::FORM_LABELS))],
            'city' => ['nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'province' => ['nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'region' => ['nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'accreditation' => ['nullable', 'string', 'max:'.self::MAX_TEXT_LENGTH],
            'program_type' => ['nullable', Rule::enum(ProgramType::class)],
            'schedule' => ['nullable', Rule::enum(ClassSchedule::class)],
            'method' => ['nullable', Rule::enum(LearningMethod::class)],
            'degree_level' => ['nullable', Rule::enum(DegreeLevel::class)],
            'fee_min' => ['nullable', 'integer', 'min:0'],
            'fee_max' => $this->feeMaxRules(),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function feeMaxRules(): array
    {
        $rules = ['nullable', 'integer', 'min:0'];

        if ($this->filled('fee_min')) {
            $rules[] = 'gte:fee_min';
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return array_filter(
            $this->safe()->except(['q', 'sort']),
            fn ($value) => filled($value),
        );
    }

    public function term(): ?string
    {
        return $this->validated('q');
    }

    public function sortKey(): ?string
    {
        return $this->validated('sort');
    }
}

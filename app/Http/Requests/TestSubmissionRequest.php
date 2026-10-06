<?php

namespace App\Http\Requests;

use App\Services\PotentialTestRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Setiap soal wajib dijawab dengan nilai yang diizinkan tes tersebut.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $registry = app(PotentialTestRegistry::class);
        $key = (string) $this->route('type');

        if (! $registry->has($key)) {
            return [];
        }

        $rules = ['answers' => ['required', 'array']];
        $allowed = $registry->allowedValues($key);

        foreach (range(0, $registry->questionCount($key) - 1) as $index) {
            $rules["answers.{$index}"] = ['required', Rule::in($allowed)];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'answers.*.required' => 'Semua pertanyaan wajib dijawab.',
            'answers.*.in' => 'Jawaban tidak valid.',
        ];
    }

    /**
     * @return array<int, int|string>
     */
    public function answers(): array
    {
        return $this->validated('answers');
    }
}

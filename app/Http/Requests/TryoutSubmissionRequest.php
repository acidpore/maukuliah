<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TryoutSubmissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'answers' => ['nullable', 'array'],
            'answers.*' => ['integer', 'min:0', 'max:9'],
        ];
    }

    /**
     * @return array<int, int|string>
     */
    public function answers(): array
    {
        return $this->validated('answers') ?? [];
    }
}

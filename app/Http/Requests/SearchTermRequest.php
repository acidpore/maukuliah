<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTermRequest extends FormRequest
{
    public const MAX_TERM_LENGTH = 100;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:'.self::MAX_TERM_LENGTH],
        ];
    }

    public function term(): ?string
    {
        return $this->validated('q');
    }
}

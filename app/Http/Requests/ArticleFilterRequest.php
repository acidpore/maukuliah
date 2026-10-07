<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleFilterRequest extends FormRequest
{
    public const MAX_TERM_LENGTH = 100;

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
            'category' => ['nullable', 'string', Rule::in(array_keys(config('articles.categories')))],
        ];
    }

    public function term(): ?string
    {
        return $this->validated('q');
    }

    public function category(): ?string
    {
        return $this->validated('category');
    }
}

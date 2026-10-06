<?php

namespace App\Http\Requests;

use App\Enums\AffiliateCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterAffiliateRequest extends FormRequest
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
            'category' => ['required', Rule::enum(AffiliateCategory::class)],
        ];
    }

    public function category(): AffiliateCategory
    {
        return AffiliateCategory::from($this->validated('category'));
    }
}

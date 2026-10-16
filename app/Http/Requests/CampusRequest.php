<?php

namespace App\Http\Requests;

use App\Models\Campus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampusRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(Campus::TYPES)],
            'form' => ['required', Rule::in(array_keys(Campus::FORM_LABELS))],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'accreditation' => ['required', 'string', 'max:10'],
            'description' => ['required', 'string'],
            'established_year' => ['nullable', 'integer', 'min:1900', 'max:'.(int) date('Y')],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }
}

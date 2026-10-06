<?php

namespace App\Http\Requests;

use App\Services\FavoriteService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleFavoriteRequest extends FormRequest
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
            'type' => ['required', Rule::in(FavoriteService::types())],
            'id' => ['required', 'integer', 'min:1'],
        ];
    }
}

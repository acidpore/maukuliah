<?php

namespace App\Http\Requests;

use App\Enums\ClassSchedule;
use App\Enums\LastEducation;
use App\Enums\ProgramType;
use App\Enums\SourceInfo;
use App\Enums\VerificationStatus;
use App\Models\StudyProgram;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreApplicationRequest extends FormRequest
{
    public const MAX_NAME_LENGTH = 100;

    public const MAX_EMAIL_LENGTH = 150;

    public const MAX_REGION_LENGTH = 100;

    // Format nomor Indonesia: awalan 0, 62, atau +62 lalu 8 dan 8-12 digit.
    public const WHATSAPP_PATTERN = '/^(\+62|62|0)8[0-9]{8,12}$/';

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
            'full_name' => ['required', 'string', 'max:'.self::MAX_NAME_LENGTH],
            'email' => ['required', 'email', 'max:'.self::MAX_EMAIL_LENGTH],
            'whatsapp' => ['required', 'regex:'.self::WHATSAPP_PATTERN],
            'last_education' => ['required', Rule::enum(LastEducation::class)],
            'region' => ['required', 'string', 'max:'.self::MAX_REGION_LENGTH],
            'major_id' => ['required', 'integer', 'exists:majors,id'],
            'program_type' => ['required', Rule::enum(ProgramType::class)],
            'schedule' => ['required', Rule::enum(ClassSchedule::class)],
            'campus_id' => [
                'required',
                'integer',
                Rule::exists('campuses', 'id')->where('verification_status', VerificationStatus::Verified->value),
            ],
            'source_info' => ['required', Rule::enum(SourceInfo::class)],
            'accepted_terms' => ['accepted'],
            'create_account' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [fn (Validator $validator) => $this->ensureCampusOffersMajor($validator)];
    }

    private function ensureCampusOffersMajor(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) {
            return;
        }

        $offered = StudyProgram::where('campus_id', $this->integer('campus_id'))
            ->where('major_id', $this->integer('major_id'))
            ->exists();

        if (! $offered) {
            $validator->errors()->add('major_id', 'Kampus yang dipilih tidak menyediakan jurusan ini.');
        }
    }
}

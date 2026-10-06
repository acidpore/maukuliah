<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationService
{
    private const GENERATED_PASSWORD_LENGTH = 40;

    public function __construct(
        private readonly LeadService $leads,
        private readonly AffiliateService $affiliates,
        private readonly RegistrationService $registration,
    ) {}

    /**
     * @param  array<string, mixed>  $data  data tervalidasi dari StoreApplicationRequest
     */
    public function submit(array $data, ?string $affiliateCode = null): Application
    {
        return DB::transaction(function () use ($data, $affiliateCode) {
            $application = Application::create([
                ...$this->applicationAttributes($data),
                'user_id' => $this->resolveAccount($data)?->getKey(),
                'status' => LeadStatus::New,
            ]);

            $this->leads->submitFromApplication($application);
            $this->affiliates->recordReferral($application, $affiliateCode);

            return $application;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applicationAttributes(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'campus_id',
            'major_id',
            'full_name',
            'email',
            'whatsapp',
            'last_education',
            'region',
            'program_type',
            'schedule',
            'source_info',
            'accepted_terms',
        ]));
    }

    /**
     * Akun dibuat hanya bila diminta dan emailnya belum terdaftar. Email yang
     * sudah ada tidak ditautkan agar pendaftaran tidak mengikat akun orang lain.
     * Kata sandi acak, pemilik akun mengaturnya lewat alur reset kata sandi.
     *
     * @param  array<string, mixed>  $data
     */
    private function resolveAccount(array $data): ?User
    {
        if (empty($data['create_account']) || User::where('email', $data['email'])->exists()) {
            return null;
        }

        return $this->registration->register([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['whatsapp'],
            'password' => Str::random(self::GENERATED_PASSWORD_LENGTH),
        ]);
    }
}

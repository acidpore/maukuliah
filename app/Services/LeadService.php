<?php

namespace App\Services;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadService
{
    /**
     * Idempoten: pengajuan ulang dari sumber yang sama tidak menggandakan lead.
     */
    public function submit(User $user, Campus $campus, LeadSource $source): Lead
    {
        return Lead::firstOrCreate(
            [
                'user_id' => $user->getKey(),
                'campus_id' => $campus->getKey(),
                'source' => $source,
            ],
            ['status' => LeadStatus::New],
        );
    }

    /**
     * Pendaftar tanpa akun tetap tercatat sebagai lead lewat data pendaftarannya.
     */
    public function submitFromApplication(Application $application): Lead
    {
        return Lead::firstOrCreate(
            ['application_id' => $application->getKey()],
            [
                'user_id' => $application->user_id,
                'campus_id' => $application->campus_id,
                'source' => LeadSource::Application,
                'status' => LeadStatus::New,
            ],
        );
    }

    public function updateStatus(Lead $lead, LeadStatus $status): Lead
    {
        $lead->update(['status' => $status]);

        return $lead;
    }

    /**
     * Admin kampus hanya melihat lead kampusnya, super admin melihat semuanya.
     */
    public function paginateFor(User $user, int $perPage): LengthAwarePaginator
    {
        return Lead::query()
            ->with(['user', 'campus', 'application'])
            ->when(
                ! $user->hasRole(UserRole::SuperAdmin),
                fn ($query) => $query->where('campus_id', $user->campus_id),
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}

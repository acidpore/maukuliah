<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AdminStatsService
{
    public const RECENT_DAYS = 7;

    /**
     * Angka ringkas dasbor. Admin kampus hanya melihat angka kampusnya;
     * jumlah kampus pending hanya untuk super admin.
     *
     * @return array{leads_by_status: array<int, array{label: string, value: int}>, leads_total: int, verified_campuses: int, pending_campuses: int|null, recent_applications: int, recent_days: int}
     */
    public function forUser(User $user): array
    {
        $isSuperAdmin = $user->hasRole(UserRole::SuperAdmin);
        $perStatus = $this->scopeToCampus(Lead::query(), $user)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'leads_by_status' => collect(LeadStatus::cases())
                ->map(fn (LeadStatus $status) => [
                    'label' => $status->label(),
                    'value' => (int) ($perStatus[$status->value] ?? 0),
                ])
                ->all(),
            'leads_total' => (int) $perStatus->sum(),
            'verified_campuses' => Campus::verified()->count(),
            'pending_campuses' => $isSuperAdmin
                ? Campus::where('verification_status', VerificationStatus::Pending->value)->count()
                : null,
            'recent_applications' => $this->scopeToCampus(Application::query(), $user)
                ->where('created_at', '>=', now()->subDays(self::RECENT_DAYS))
                ->count(),
            'recent_days' => self::RECENT_DAYS,
        ];
    }

    private function scopeToCampus(Builder $query, User $user): Builder
    {
        return $query->when(
            ! $user->hasRole(UserRole::SuperAdmin),
            fn (Builder $builder) => $builder->where('campus_id', $user->campus_id),
        );
    }
}

<?php

namespace App\Services;

use App\Enums\VerificationStatus;
use App\Models\Campus;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\Gate;

class CampusVerificationService
{
    /**
     * Pemilik kampus mengajukan data untuk diperiksa pengelola platform.
     */
    public function submit(Campus $campus, User $actor): Campus
    {
        Gate::forUser($actor)->authorize('submitForVerification', $campus);

        $this->ensureStatus($campus, [VerificationStatus::Draft, VerificationStatus::Rejected]);

        return $this->transition($campus, VerificationStatus::Pending);
    }

    public function approve(Campus $campus, User $actor): Campus
    {
        Gate::forUser($actor)->authorize('verify', Campus::class);

        $this->ensureStatus($campus, [VerificationStatus::Pending]);

        $campus->verified_at = now();
        $campus->verified_by = $actor->getKey();

        return $this->transition($campus, VerificationStatus::Verified);
    }

    public function reject(Campus $campus, User $actor): Campus
    {
        Gate::forUser($actor)->authorize('verify', Campus::class);

        $this->ensureStatus($campus, [VerificationStatus::Pending]);

        $campus->verified_at = null;
        $campus->verified_by = null;

        return $this->transition($campus, VerificationStatus::Rejected);
    }

    /**
     * @param  array<int, VerificationStatus>  $allowed
     */
    private function ensureStatus(Campus $campus, array $allowed): void
    {
        if (! in_array($campus->verification_status, $allowed, true)) {
            throw new DomainException(
                "Transisi tidak diizinkan dari status {$campus->verification_status->value}.",
            );
        }
    }

    private function transition(Campus $campus, VerificationStatus $status): Campus
    {
        $campus->verification_status = $status;
        $campus->save();

        return $campus;
    }
}

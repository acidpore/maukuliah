<?php

namespace App\Services;

use App\Enums\AffiliateCategory;
use App\Enums\CommissionStatus;
use App\Enums\ReferralStatus;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\Application;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AffiliateService
{
    /**
     * Idempoten: satu pengguna hanya punya satu akun afiliasi.
     */
    public function register(User $user, AffiliateCategory $category): Affiliate
    {
        return Affiliate::firstOrCreate(
            ['user_id' => $user->getKey()],
            ['code' => $this->generateUniqueCode(), 'category' => $category],
        );
    }

    public function findByCode(?string $code): ?Affiliate
    {
        if (blank($code)) {
            return null;
        }

        return Affiliate::where('code', Str::upper($code))->first();
    }

    /**
     * Rujukan diri sendiri tidak dihitung agar komisi tidak bisa dimanipulasi.
     */
    public function recordReferral(Application $application, ?string $code): ?AffiliateReferral
    {
        $affiliate = $this->findByCode($code);

        if ($affiliate === null || $this->isSelfReferral($affiliate, $application)) {
            return null;
        }

        return AffiliateReferral::firstOrCreate(
            ['application_id' => $application->getKey()],
            ['affiliate_id' => $affiliate->getKey(), 'status' => ReferralStatus::Registered],
        );
    }

    /**
     * Komisi hanya dibuat bila pembayaran masuk dalam jangka waktu yang diizinkan.
     */
    public function markPaid(AffiliateReferral $referral): AffiliateReferral
    {
        if ($referral->status !== ReferralStatus::Registered) {
            return $referral;
        }

        return DB::transaction(function () use ($referral) {
            if ($this->isPastPaymentWindow($referral)) {
                $referral->update(['status' => ReferralStatus::Rejected]);

                return $referral;
            }

            $referral->update(['status' => ReferralStatus::Paid, 'paid_at' => now()]);

            Commission::create([
                'affiliate_referral_id' => $referral->getKey(),
                'amount' => (int) config('affiliate.commission_per_student'),
                'status' => CommissionStatus::Pending,
            ]);

            return $referral;
        });
    }

    /**
     * Komisi tertunda mencakup yang menunggu persetujuan dan yang sudah disetujui
     * tetapi belum dibayarkan.
     *
     * @return array{total: int, paid: int, pending_commission: int, paid_commission: int}
     */
    public function stats(Affiliate $affiliate): array
    {
        $commissions = $affiliate->commissions();

        return [
            'total' => $affiliate->referrals()->count(),
            'paid' => $affiliate->referrals()->where('status', ReferralStatus::Paid)->count(),
            'pending_commission' => (int) (clone $commissions)
                ->whereIn('commissions.status', [CommissionStatus::Pending, CommissionStatus::Approved])
                ->sum('commissions.amount'),
            'paid_commission' => (int) (clone $commissions)
                ->where('commissions.status', CommissionStatus::Paid)
                ->sum('commissions.amount'),
        ];
    }

    public function approveCommission(Commission $commission): Commission
    {
        if ($commission->status === CommissionStatus::Pending) {
            $commission->update(['status' => CommissionStatus::Approved]);
        }

        return $commission;
    }

    public function markCommissionPaid(Commission $commission): Commission
    {
        if ($commission->status === CommissionStatus::Approved) {
            $commission->update(['status' => CommissionStatus::Paid, 'paid_at' => now()]);
        }

        return $commission;
    }

    private function isSelfReferral(Affiliate $affiliate, Application $application): bool
    {
        return strcasecmp((string) $affiliate->user?->email, $application->email) === 0;
    }

    private function isPastPaymentWindow(AffiliateReferral $referral): bool
    {
        $deadline = $referral->created_at->copy()
            ->addDays((int) config('affiliate.payment_window_days'));

        return now()->greaterThan($deadline);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random((int) config('affiliate.code_length')));
        } while (Affiliate::where('code', $code)->exists());

        return $code;
    }
}

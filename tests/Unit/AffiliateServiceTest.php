<?php

namespace Tests\Unit;

use App\Enums\AffiliateCategory;
use App\Enums\CommissionStatus;
use App\Enums\ReferralStatus;
use App\Models\Application;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateServiceTest extends TestCase
{
    use RefreshDatabase;

    private AffiliateService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AffiliateService;
        config([
            'affiliate.commission_per_student' => 250000,
            'affiliate.payment_window_days' => 60,
        ]);
    }

    public function test_register_is_idempotent_and_generates_unique_code(): void
    {
        $user = User::factory()->create();

        $first = $this->service->register($user, AffiliateCategory::Umum);
        $second = $this->service->register($user, AffiliateCategory::Dosen);

        $this->assertTrue($first->is($second));
        $this->assertSame(config('affiliate.code_length'), strlen($first->code));
        $this->assertSame(AffiliateCategory::Umum, $first->category);
    }

    public function test_records_referral_for_valid_code_case_insensitively(): void
    {
        $affiliate = $this->service->register(User::factory()->create(), AffiliateCategory::Umum);
        $application = Application::factory()->create();

        $referral = $this->service->recordReferral($application, strtolower($affiliate->code));

        $this->assertSame($affiliate->id, $referral->affiliate_id);
        $this->assertSame(ReferralStatus::Registered, $referral->status);
    }

    public function test_ignores_unknown_or_missing_code(): void
    {
        $application = Application::factory()->create();

        $this->assertNull($this->service->recordReferral($application, 'TIDAKADA'));
        $this->assertNull($this->service->recordReferral($application, null));
        $this->assertDatabaseCount('affiliate_referrals', 0);
    }

    public function test_ignores_self_referral(): void
    {
        $user = User::factory()->create();
        $affiliate = $this->service->register($user, AffiliateCategory::Mahasiswa);
        $application = Application::factory()->create(['email' => strtoupper($user->email)]);

        $this->assertNull($this->service->recordReferral($application, $affiliate->code));
    }

    public function test_mark_paid_creates_pending_commission_from_config(): void
    {
        $affiliate = $this->service->register(User::factory()->create(), AffiliateCategory::Umum);
        $referral = $this->service->recordReferral(Application::factory()->create(), $affiliate->code);

        $this->service->markPaid($referral);

        $referral->refresh();
        $this->assertSame(ReferralStatus::Paid, $referral->status);
        $this->assertNotNull($referral->paid_at);
        $this->assertSame(250000, $referral->commission->amount);
        $this->assertSame(CommissionStatus::Pending, $referral->commission->status);
    }

    public function test_mark_paid_after_window_rejects_without_commission(): void
    {
        $affiliate = $this->service->register(User::factory()->create(), AffiliateCategory::Umum);
        $referral = $this->service->recordReferral(Application::factory()->create(), $affiliate->code);
        $referral->forceFill(['created_at' => now()->subDays(61)])->save();

        $this->service->markPaid($referral);

        $this->assertSame(ReferralStatus::Rejected, $referral->fresh()->status);
        $this->assertDatabaseCount('commissions', 0);
    }

    public function test_mark_paid_is_idempotent(): void
    {
        $affiliate = $this->service->register(User::factory()->create(), AffiliateCategory::Umum);
        $referral = $this->service->recordReferral(Application::factory()->create(), $affiliate->code);

        $this->service->markPaid($referral);
        $this->service->markPaid($referral->fresh());

        $this->assertDatabaseCount('commissions', 1);
    }

    public function test_commission_moves_from_pending_to_approved_to_paid_in_order(): void
    {
        $affiliate = $this->service->register(User::factory()->create(), AffiliateCategory::Umum);
        $referral = $this->service->recordReferral(Application::factory()->create(), $affiliate->code);
        $commission = $this->service->markPaid($referral)->commission;

        $this->service->markCommissionPaid($commission);
        $this->assertSame(CommissionStatus::Pending, $commission->fresh()->status);

        $this->service->approveCommission($commission);
        $this->service->markCommissionPaid($commission->fresh());

        $this->assertSame(CommissionStatus::Paid, $commission->fresh()->status);
        $this->assertNotNull($commission->fresh()->paid_at);
    }
}

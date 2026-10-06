<?php

namespace Tests\Feature;

use App\Enums\AffiliateCategory;
use App\Models\Application;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\StubsViews;
use Tests\TestCase;

class AffiliateRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViews(['affiliate.index', 'affiliate.dashboard']);
    }

    public function test_index_is_public_and_exposes_program_terms(): void
    {
        $response = $this->get('/affiliate')->assertOk()->assertViewIs('affiliate.index');

        $this->assertSame(AffiliateCategory::cases(), $response->viewData('categories'));
        $this->assertSame(config('affiliate.commission_per_student'), $response->viewData('commissionPerStudent'));
        $this->assertSame(config('affiliate.payment_window_days'), $response->viewData('paymentWindowDays'));
        $this->assertNull($response->viewData('affiliate'));
    }

    public function test_register_and_dashboard_require_login(): void
    {
        $this->post('/affiliate/register', ['category' => 'umum'])->assertRedirect(route('login'));
        $this->get('/affiliate/dashboard')->assertRedirect(route('login'));
    }

    public function test_user_can_register_as_affiliate(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/affiliate/register', ['category' => 'dosen'])
            ->assertRedirect(route('affiliate.dashboard'));

        $this->assertSame(AffiliateCategory::Dosen, $user->fresh()->affiliate->category);
    }

    public function test_register_rejects_unknown_category(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/affiliate/register', ['category' => 'alien'])
            ->assertSessionHasErrors('category');
    }

    public function test_dashboard_redirects_when_not_an_affiliate(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/affiliate/dashboard')
            ->assertRedirect(route('affiliate.index'));
    }

    public function test_dashboard_shows_only_own_referrals_and_stats(): void
    {
        $affiliates = app(AffiliateService::class);
        $mine = User::factory()->create();
        $theirs = User::factory()->create();
        $myAffiliate = $affiliates->register($mine, AffiliateCategory::Umum);
        $otherAffiliate = $affiliates->register($theirs, AffiliateCategory::Umum);

        $paid = $affiliates->recordReferral(Application::factory()->create(), $myAffiliate->code);
        $affiliates->recordReferral(Application::factory()->create(), $myAffiliate->code);
        $affiliates->recordReferral(Application::factory()->create(), $otherAffiliate->code);
        $affiliates->markPaid($paid);

        $response = $this->actingAs($mine)->get('/affiliate/dashboard')
            ->assertOk()
            ->assertViewIs('affiliate.dashboard');

        $this->assertSame(
            ['total' => 2, 'paid' => 1, 'pending_commission' => config('affiliate.commission_per_student'), 'paid_commission' => 0],
            $response->viewData('stats'),
        );
        $this->assertCount(2, $response->viewData('referrals'));
        $this->assertStringContainsString('ref='.$myAffiliate->code, $response->viewData('referralUrl'));
    }
}

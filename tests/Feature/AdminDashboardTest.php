<?php

namespace Tests\Feature;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Lead;
use App\Models\User;
use App\Services\AdminStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function leadFor(Campus $campus, LeadStatus $status = LeadStatus::New): Lead
    {
        return Lead::create([
            'user_id' => User::factory()->create()->id,
            'campus_id' => $campus->id,
            'source' => LeadSource::Brochure,
            'status' => $status,
        ]);
    }

    private function applicationFor(Campus $campus, int $daysAgo): void
    {
        $application = Application::factory()->create(['campus_id' => $campus->id]);
        $application->forceFill(['created_at' => now()->subDays($daysAgo)])->save();
    }

    public function test_super_admin_stats_cover_all_campuses(): void
    {
        $campus = Campus::factory()->create();
        Campus::factory()->unverified(VerificationStatus::Pending)->create();
        $this->leadFor($campus);
        $this->leadFor($campus, LeadStatus::Accepted);
        $this->leadFor(Campus::factory()->create());

        $stats = app(AdminStatsService::class)
            ->forUser(User::factory()->create(['role' => UserRole::SuperAdmin]));

        $this->assertSame(3, $stats['leads_total']);
        $this->assertSame(2, $stats['verified_campuses']);
        $this->assertSame(1, $stats['pending_campuses']);
        $this->assertSame(
            ['Baru' => 2, 'Dihubungi' => 0, 'Mendaftar' => 0, 'Diterima' => 1],
            collect($stats['leads_by_status'])->pluck('value', 'label')->all(),
        );
    }

    public function test_campus_admin_stats_are_limited_to_own_campus(): void
    {
        $campus = Campus::factory()->create();
        $this->leadFor($campus);
        $this->leadFor(Campus::factory()->create());
        $this->applicationFor($campus, 1);
        $this->applicationFor($campus, AdminStatsService::RECENT_DAYS + 3);
        $this->applicationFor(Campus::factory()->create(), 1);

        $stats = app(AdminStatsService::class)->forUser(
            User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]),
        );

        $this->assertSame(1, $stats['leads_total']);
        $this->assertSame(1, $stats['recent_applications']);
        $this->assertNull($stats['pending_campuses']);
    }

    public function test_dashboard_uses_admin_layout_without_public_chrome(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]))
            ->get('/admin')
            ->assertOk()
            ->assertSee('admin-sidebar', false)
            ->assertSee('Verifikasi kampus')
            ->assertDontSee('site-header', false)
            ->assertDontSee('site-footer', false);
    }

    public function test_campus_admin_menu_hides_verification_link(): void
    {
        $campus = Campus::factory()->create();

        $this->actingAs(User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]))
            ->get('/admin')
            ->assertOk()
            ->assertDontSee('Verifikasi kampus');
    }

    public function test_verification_page_renders_in_admin_layout(): void
    {
        Campus::factory()->unverified(VerificationStatus::Pending)->create(['name' => 'Kampus Uji Antrean']);

        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]))
            ->get('/admin/campuses')
            ->assertOk()
            ->assertSee('Kampus Uji Antrean')
            ->assertSee('admin-sidebar', false);
    }
}

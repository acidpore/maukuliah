<?php

namespace Tests\Feature;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Campus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\StubsViews;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViews(['admin.dashboard', 'admin.campuses.index']);
    }

    private function superAdmin(): User
    {
        return User::factory()->create(['role' => UserRole::SuperAdmin]);
    }

    private function campusAdmin(Campus $campus): User
    {
        return User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]);
    }

    private function leadFor(Campus $campus): Lead
    {
        return Lead::create([
            'user_id' => User::factory()->create()->id,
            'campus_id' => $campus->id,
            'source' => LeadSource::Brochure,
            'status' => LeadStatus::New,
        ]);
    }

    public function test_admin_area_blocks_guests_and_students(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->create())->get('/admin/campuses')->assertForbidden();
    }

    public function test_campus_admin_sees_only_own_campus_leads(): void
    {
        $campus = Campus::factory()->create();
        $otherCampus = Campus::factory()->create();
        $this->leadFor($campus);
        $this->leadFor($otherCampus);

        $response = $this->actingAs($this->campusAdmin($campus))->get('/admin')
            ->assertOk()
            ->assertViewIs('admin.dashboard');

        $this->assertCount(1, $response->viewData('leads'));
        $this->assertTrue($response->viewData('campus')->is($campus));
        $this->assertSame(LeadStatus::cases(), $response->viewData('statusOptions'));
    }

    public function test_super_admin_sees_all_leads(): void
    {
        $this->leadFor(Campus::factory()->create());
        $this->leadFor(Campus::factory()->create());

        $response = $this->actingAs($this->superAdmin())->get('/admin')->assertOk();

        $this->assertCount(2, $response->viewData('leads'));
        $this->assertNull($response->viewData('campus'));
    }

    public function test_campus_admin_updates_status_of_own_lead(): void
    {
        $campus = Campus::factory()->create();
        $lead = $this->leadFor($campus);

        $this->actingAs($this->campusAdmin($campus))
            ->post(route('admin.leads.status', $lead), ['status' => 'contacted'])
            ->assertRedirect();

        $this->assertSame(LeadStatus::Contacted, $lead->fresh()->status);
    }

    public function test_campus_admin_cannot_update_other_campus_lead(): void
    {
        $lead = $this->leadFor(Campus::factory()->create());

        $this->actingAs($this->campusAdmin(Campus::factory()->create()))
            ->post(route('admin.leads.status', $lead), ['status' => 'accepted'])
            ->assertForbidden();

        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_lead_status_must_be_valid(): void
    {
        $lead = $this->leadFor(Campus::factory()->create());

        $this->actingAs($this->superAdmin())
            ->post(route('admin.leads.status', $lead), ['status' => 'entah'])
            ->assertSessionHasErrors('status');
    }

    public function test_only_super_admin_lists_pending_campuses(): void
    {
        $pending = Campus::factory()->unverified(VerificationStatus::Pending)->create();
        Campus::factory()->create();
        Campus::factory()->unverified()->create();

        $this->actingAs($this->campusAdmin($pending))->get('/admin/campuses')->assertForbidden();

        $response = $this->actingAs($this->superAdmin())->get('/admin/campuses')
            ->assertOk()
            ->assertViewIs('admin.campuses.index');

        $this->assertCount(1, $response->viewData('campuses'));
    }

    public function test_campus_admin_submits_then_super_admin_approves(): void
    {
        $campus = Campus::factory()->unverified()->create();

        $this->actingAs($this->campusAdmin($campus))
            ->post(route('admin.campuses.submit', $campus))
            ->assertRedirect();
        $this->assertSame(VerificationStatus::Pending, $campus->fresh()->verification_status);

        $this->actingAs($this->superAdmin())
            ->post(route('admin.campuses.approve', $campus))
            ->assertRedirect();
        $this->assertSame(VerificationStatus::Verified, $campus->fresh()->verification_status);
    }

    public function test_super_admin_can_reject_pending_campus(): void
    {
        $campus = Campus::factory()->unverified(VerificationStatus::Pending)->create();

        $this->actingAs($this->superAdmin())->post(route('admin.campuses.reject', $campus));

        $this->assertSame(VerificationStatus::Rejected, $campus->fresh()->verification_status);
    }

    public function test_campus_admin_cannot_approve_or_submit_for_another_campus(): void
    {
        $campus = Campus::factory()->unverified(VerificationStatus::Pending)->create();
        $admin = $this->campusAdmin(Campus::factory()->create());

        $this->actingAs($admin)->post(route('admin.campuses.approve', $campus))->assertForbidden();
        $this->actingAs($admin)->post(route('admin.campuses.submit', $campus))->assertForbidden();
    }

    public function test_invalid_transition_returns_message_instead_of_error(): void
    {
        $campus = Campus::factory()->create();

        $this->actingAs($this->superAdmin())
            ->post(route('admin.campuses.approve', $campus))
            ->assertSessionHasErrors('status');
    }
}

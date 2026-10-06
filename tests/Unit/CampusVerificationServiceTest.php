<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Campus;
use App\Models\User;
use App\Services\CampusVerificationService;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusVerificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampusVerificationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CampusVerificationService;
    }

    public function test_campus_admin_submits_own_draft_campus(): void
    {
        $campus = Campus::factory()->unverified()->create();
        $admin = User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]);

        $this->service->submit($campus, $admin);

        $this->assertSame(VerificationStatus::Pending, $campus->fresh()->verification_status);
    }

    public function test_campus_admin_cannot_submit_another_campus(): void
    {
        $campus = Campus::factory()->unverified()->create();
        $other = Campus::factory()->create();
        $admin = User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $other->id]);

        $this->expectException(AuthorizationException::class);

        $this->service->submit($campus, $admin);
    }

    public function test_super_admin_approves_pending_campus(): void
    {
        $campus = Campus::factory()->unverified(VerificationStatus::Pending)->create();
        $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        $this->service->approve($campus, $superAdmin);

        $campus->refresh();
        $this->assertTrue($campus->isVerified());
        $this->assertSame($superAdmin->id, $campus->verified_by);
        $this->assertNotNull($campus->verified_at);
    }

    public function test_super_admin_rejects_pending_campus_and_owner_can_resubmit(): void
    {
        $campus = Campus::factory()->unverified(VerificationStatus::Pending)->create();
        $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);
        $admin = User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]);

        $this->service->reject($campus, $superAdmin);
        $this->assertSame(VerificationStatus::Rejected, $campus->fresh()->verification_status);

        $this->service->submit($campus->fresh(), $admin);
        $this->assertSame(VerificationStatus::Pending, $campus->fresh()->verification_status);
    }

    public function test_campus_admin_cannot_approve(): void
    {
        $campus = Campus::factory()->unverified(VerificationStatus::Pending)->create();
        $admin = User::factory()->create(['role' => UserRole::CampusAdmin, 'campus_id' => $campus->id]);

        $this->expectException(AuthorizationException::class);

        $this->service->approve($campus, $admin);
    }

    public function test_cannot_approve_campus_that_is_not_pending(): void
    {
        $campus = Campus::factory()->unverified()->create();
        $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        $this->expectException(DomainException::class);

        $this->service->approve($campus, $superAdmin);
    }
}

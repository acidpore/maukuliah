<?php

namespace Tests\Unit;

use App\Enums\AffiliateCategory;
use App\Enums\LeadSource;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\AffiliateService;
use App\Services\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ApplicationService::class);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        $program = StudyProgram::factory()->create();

        return array_merge([
            'campus_id' => $program->campus_id,
            'major_id' => $program->major_id,
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'whatsapp' => '081234567890',
            'last_education' => 'sma',
            'region' => 'Bogor',
            'program_type' => 'karyawan',
            'schedule' => 'malam',
            'source_info' => 'website',
            'accepted_terms' => true,
        ], $overrides);
    }

    public function test_submit_stores_application_and_lead_without_account(): void
    {
        $application = $this->service->submit($this->payload());

        $this->assertNull($application->user_id);
        $this->assertSame('Budi Santoso', $application->full_name);
        $this->assertSame(LeadSource::Application, $application->lead->source);
        $this->assertSame($application->campus_id, $application->lead->campus_id);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_submit_creates_account_when_requested(): void
    {
        $application = $this->service->submit($this->payload(['create_account' => true]));

        $this->assertNotNull($application->user_id);
        $this->assertSame('budi@example.test', $application->user->email);
        $this->assertSame($application->user_id, $application->lead->user_id);
    }

    public function test_submit_does_not_link_existing_account_with_same_email(): void
    {
        User::factory()->create(['email' => 'budi@example.test']);

        $application = $this->service->submit($this->payload(['create_account' => true]));

        $this->assertNull($application->user_id);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_submit_records_affiliate_referral(): void
    {
        $affiliate = app(AffiliateService::class)->register(User::factory()->create(), AffiliateCategory::Umum);

        $application = $this->service->submit($this->payload(), $affiliate->code);

        $this->assertSame($affiliate->id, $application->referral->affiliate_id);
    }

    public function test_submit_ignores_unknown_fields(): void
    {
        $application = $this->service->submit($this->payload(['status' => 'accepted', 'user_id' => 999]));

        $this->assertSame('new', $application->status->value);
        $this->assertNull($application->user_id);
    }
}

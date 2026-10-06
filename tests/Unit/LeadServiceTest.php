<?php

namespace Tests\Unit;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Models\Campus;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_creates_new_lead(): void
    {
        $lead = (new LeadService)->submit(User::factory()->create(), Campus::factory()->create(), LeadSource::Brochure);

        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertSame(LeadSource::Brochure, $lead->source);
    }

    public function test_submit_is_idempotent_per_source(): void
    {
        $service = new LeadService;
        $user = User::factory()->create();
        $campus = Campus::factory()->create();

        $first = $service->submit($user, $campus, LeadSource::Application);
        $second = $service->submit($user, $campus, LeadSource::Application);
        $service->submit($user, $campus, LeadSource::Brochure);

        $this->assertTrue($first->is($second));
        $this->assertDatabaseCount('leads', 2);
    }

    public function test_update_status_changes_lead(): void
    {
        $service = new LeadService;
        $lead = $service->submit(User::factory()->create(), Campus::factory()->create(), LeadSource::Application);

        $service->updateStatus($lead, LeadStatus::Contacted);

        $this->assertSame(LeadStatus::Contacted, $lead->fresh()->status);
    }
}

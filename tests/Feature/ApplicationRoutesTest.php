<?php

namespace Tests\Feature;

use App\Enums\AffiliateCategory;
use App\Models\Application;
use App\Models\Campus;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function payload(StudyProgram $program, array $overrides = []): array
    {
        return array_merge([
            'campus_id' => $program->campus_id,
            'major_id' => $program->major_id,
            'full_name' => 'Siti Aminah',
            'email' => 'siti@example.test',
            'whatsapp' => '081234567890',
            'last_education' => 'sma',
            'region' => 'Bogor',
            'program_type' => 'karyawan',
            'schedule' => 'malam',
            'source_info' => 'website',
            'accepted_terms' => '1',
        ], $overrides);
    }

    public function test_form_page_renders_with_options(): void
    {
        $this->get('/daftar-kuliah')
            ->assertOk()
            ->assertViewIs('applications.create')
            ->assertViewHasAll(['campuses', 'majors', 'options']);
    }

    public function test_guest_can_submit_application(): void
    {
        $program = StudyProgram::factory()->create();

        $this->post('/daftar-kuliah', $this->payload($program))
            ->assertRedirect(route('applications.create'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('applications', ['email' => 'siti@example.test', 'user_id' => null]);
        $this->assertDatabaseCount('leads', 1);
    }

    public function test_requires_terms_and_valid_whatsapp(): void
    {
        $program = StudyProgram::factory()->create();

        $this->post('/daftar-kuliah', $this->payload($program, ['accepted_terms' => null, 'whatsapp' => '12345']))
            ->assertSessionHasErrors(['accepted_terms', 'whatsapp']);

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_rejects_campus_that_does_not_offer_major(): void
    {
        $program = StudyProgram::factory()->create();
        $otherMajor = StudyProgram::factory()->create();

        $this->post('/daftar-kuliah', $this->payload($program, ['major_id' => $otherMajor->major_id]))
            ->assertSessionHasErrors('major_id');
    }

    public function test_rejects_unverified_campus(): void
    {
        $program = StudyProgram::factory()->create();
        $program->campus->update(['verification_status' => 'draft']);

        $this->post('/daftar-kuliah', $this->payload($program))->assertSessionHasErrors('campus_id');
    }

    public function test_referral_code_from_query_is_captured_and_attributed(): void
    {
        $affiliate = app(AffiliateService::class)->register(User::factory()->create(), AffiliateCategory::Umum);
        $program = StudyProgram::factory()->create();

        $this->get('/?ref='.$affiliate->code)->assertCookie(config('affiliate.cookie.name'));

        $this->withCookie(config('affiliate.cookie.name'), $affiliate->code)
            ->post('/daftar-kuliah', $this->payload($program));

        $application = Application::first();
        $this->assertSame($affiliate->id, $application->referral->affiliate_id);
    }

    public function test_unknown_referral_code_sets_no_cookie(): void
    {
        $this->get('/?ref=TIDAKADA')->assertCookieMissing(config('affiliate.cookie.name'));
    }

    public function test_submission_is_rate_limited(): void
    {
        $program = StudyProgram::factory()->create();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/daftar-kuliah', $this->payload($program, ['email' => "u{$attempt}@example.test"]));
        }

        $this->post('/daftar-kuliah', $this->payload($program))->assertStatus(429);
    }

    public function test_form_preselects_verified_campus_from_query(): void
    {
        $campus = Campus::factory()->create();
        $hidden = Campus::factory()->unverified()->create();

        $this->get('/daftar-kuliah?campus='.$campus->slug)
            ->assertViewHas('selectedCampusId', $campus->id);

        $this->get('/daftar-kuliah?campus='.$hidden->slug)->assertViewHas('selectedCampusId', null);
        $this->get('/daftar-kuliah?campus=tidak-ada')->assertViewHas('selectedCampusId', null);
        $this->get('/daftar-kuliah')->assertViewHas('selectedCampusId', null);
    }
}

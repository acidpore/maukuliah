<?php

namespace Tests\Feature;

use App\Enums\LeadSource;
use App\Models\Brochure;
use App\Models\Campus;
use App\Models\Career;
use App\Models\Lead;
use App\Models\Major;
use App\Models\Scholarship;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Support\StubsViews;
use Tests\TestCase;

class FavoriteAndBrochureRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViews(['favorites.index', 'campuses.show', 'majors.show', 'careers.show', 'scholarships.show']);
    }

    public function test_favorite_actions_require_login(): void
    {
        $this->get('/favourites')->assertRedirect(route('login'));
        $this->post('/favorites', ['type' => 'campus', 'id' => 1])->assertRedirect(route('login'));
    }

    public function test_toggle_adds_then_removes_favorite(): void
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $payload = ['type' => 'campus', 'id' => $campus->id];

        $this->actingAs($user)->post('/favorites', $payload)->assertRedirect();
        $this->assertSame(1, $user->favorites()->count());

        $this->actingAs($user)->post('/favorites', $payload)->assertRedirect();
        $this->assertSame(0, $user->favorites()->count());
    }

    public function test_every_favoritable_type_can_be_toggled(): void
    {
        $user = User::factory()->create();
        $items = [
            'campus' => Campus::factory()->create(),
            'major' => Major::factory()->create(),
            'career' => Career::factory()->create(),
            'scholarship' => Scholarship::factory()->create(),
        ];

        foreach ($items as $type => $item) {
            $this->actingAs($user)->post('/favorites', ['type' => $type, 'id' => $item->id]);
        }

        $this->assertSame(4, $user->favorites()->count());
    }

    public function test_toggle_rejects_unknown_type_and_missing_item(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/favorites', ['type' => 'hewan', 'id' => 1])
            ->assertSessionHasErrors('type');

        $this->actingAs($user)->post('/favorites', ['type' => 'campus', 'id' => 999])
            ->assertNotFound();
    }

    public function test_unverified_campus_cannot_be_favorited(): void
    {
        $campus = Campus::factory()->unverified()->create();

        $this->actingAs(User::factory()->create())
            ->post('/favorites', ['type' => 'campus', 'id' => $campus->id])
            ->assertNotFound();
    }

    public function test_index_groups_favorites_by_type_for_the_owner_only(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $campus = Campus::factory()->create();
        $major = Major::factory()->create();

        $this->actingAs($me)->post('/favorites', ['type' => 'campus', 'id' => $campus->id]);
        $this->actingAs($me)->post('/favorites', ['type' => 'major', 'id' => $major->id]);
        $this->actingAs($other)->post('/favorites', ['type' => 'major', 'id' => Major::factory()->create()->id]);

        $favorites = $this->actingAs($me)->get('/favourites')
            ->assertOk()
            ->assertViewIs('favorites.index')
            ->viewData('favorites');

        $this->assertSame(['campuses', 'majors', 'careers', 'scholarships'], array_keys($favorites));
        $this->assertCount(1, $favorites['campuses']);
        $this->assertCount(1, $favorites['majors']);
        $this->assertCount(0, $favorites['careers']);
    }

    public function test_detail_pages_report_favorite_state(): void
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();

        $this->get(route('campuses.show', $campus))->assertViewHas('isFavorited', false);

        $this->actingAs($user)->post('/favorites', ['type' => 'campus', 'id' => $campus->id]);
        $this->actingAs($user)->get(route('campuses.show', $campus))->assertViewHas('isFavorited', true);
    }

    public function test_campus_detail_exposes_extra_contract_data(): void
    {
        $campus = Campus::factory()->create();
        Brochure::factory()->create(['campus_id' => $campus->id]);

        $response = $this->get(route('campuses.show', $campus))->assertOk();

        foreach (['campus', 'brochures', 'admissionPeriods', 'studyPrograms', 'faqs', 'isFavorited'] as $key) {
            $response->assertViewHas($key);
        }

        $this->assertCount(1, $response->viewData('brochures'));
    }

    public function test_brochure_download_requires_login(): void
    {
        $brochure = Brochure::factory()->create();

        $this->get(route('brochures.download', [$brochure->campus, $brochure]))
            ->assertRedirect(route('login'));
    }

    public function test_brochure_download_streams_file_and_records_lead(): void
    {
        Storage::fake();
        $user = User::factory()->create();
        $brochure = Brochure::factory()->create(['file_path' => 'brochures/uji.pdf']);
        Storage::put('brochures/uji.pdf', 'isi pdf');

        $response = $this->actingAs($user)
            ->get(route('brochures.download', [$brochure->campus, $brochure]));

        $response->assertOk()->assertDownload('uji.pdf');
        $this->assertDatabaseHas('leads', [
            'user_id' => $user->id,
            'campus_id' => $brochure->campus_id,
            'source' => LeadSource::Brochure->value,
        ]);
    }

    public function test_missing_brochure_file_is_not_found_and_records_no_lead(): void
    {
        Storage::fake();
        $brochure = Brochure::factory()->create(['file_path' => 'brochures/tidak-ada.pdf']);

        $this->actingAs(User::factory()->create())
            ->get(route('brochures.download', [$brochure->campus, $brochure]))
            ->assertNotFound();

        $this->assertSame(0, Lead::count());
    }

    public function test_brochure_of_another_campus_is_not_found(): void
    {
        Storage::fake();
        $brochure = Brochure::factory()->create(['file_path' => 'brochures/uji.pdf']);
        Storage::put('brochures/uji.pdf', 'isi pdf');
        $otherCampus = Campus::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('brochures.download', [$otherCampus, $brochure]))
            ->assertNotFound();
    }

    public function test_brochure_of_unverified_campus_is_not_found(): void
    {
        Storage::fake();
        $campus = Campus::factory()->unverified()->create();
        $brochure = Brochure::factory()->create(['campus_id' => $campus->id, 'file_path' => 'brochures/uji.pdf']);
        Storage::put('brochures/uji.pdf', 'isi pdf');

        $this->actingAs(User::factory()->create())
            ->get(route('brochures.download', [$campus, $brochure]))
            ->assertNotFound();
    }

    public function test_favorite_groups_carry_relation_counts_for_cards(): void
    {
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $major = Major::factory()->create();
        StudyProgram::factory()->create(['campus_id' => $campus->id, 'major_id' => $major->id]);

        $this->actingAs($user)->post('/favorites', ['type' => 'campus', 'id' => $campus->id]);
        $this->actingAs($user)->post('/favorites', ['type' => 'major', 'id' => $major->id]);

        $favorites = $this->actingAs($user)->get('/favourites')->viewData('favorites');

        $this->assertSame(1, $favorites['campuses'][0]->majors_count);
        $this->assertSame(1, $favorites['majors'][0]->campuses_count);
    }
}

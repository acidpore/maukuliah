<?php

namespace Tests\Feature;

use App\Enums\ClassSchedule;
use App\Models\Campus;
use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusCollectionRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_page_lists_matching_campuses_with_heading(): void
    {
        $night = Campus::factory()->create(['name' => 'Kampus Malam']);
        $morning = Campus::factory()->create(['name' => 'Kampus Pagi']);
        StudyProgram::factory()->create(['campus_id' => $night->id, 'schedules' => [ClassSchedule::Malam]]);
        StudyProgram::factory()->create(['campus_id' => $morning->id]);

        $response = $this->get('/jadwal-kuliah/malam');

        $response->assertOk()
            ->assertViewIs('campuses.index')
            ->assertViewHas('heading', 'Kuliah Malam')
            ->assertViewHas('intro')
            ->assertSee('Kampus Malam')
            ->assertDontSee('Kampus Pagi');
    }

    public function test_schedule_page_supports_hyphenated_value_and_region(): void
    {
        $campus = Campus::factory()->create(['name' => 'Kampus Bogor', 'city' => 'Bogor', 'province' => 'Jawa Barat']);
        StudyProgram::factory()->create(['campus_id' => $campus->id, 'schedules' => [ClassSchedule::AkhirPekan]]);

        $this->get('/jadwal-kuliah/akhir-pekan/bogor')
            ->assertOk()
            ->assertViewHas('heading', 'Kuliah Akhir Pekan di Bogor')
            ->assertSee('Kampus Bogor');
    }

    public function test_unknown_schedule_or_region_returns_not_found(): void
    {
        Campus::factory()->create();

        $this->get('/jadwal-kuliah/tengah-malam')->assertNotFound();
        $this->get('/jadwal-kuliah/malam/wilayah-tidak-ada')->assertNotFound();
    }

    public function test_program_and_method_pages_render(): void
    {
        $this->get('/program-kuliah/rpl')->assertOk()->assertViewHas('heading', 'Rekognisi Pembelajaran Lampau');
        $this->get('/metode-belajar/full-online')->assertOk()->assertViewHas('heading', 'Kuliah Full Online');
        $this->get('/program-kuliah/tidak-ada')->assertNotFound();
    }

    public function test_unverified_campus_is_not_publicly_visible(): void
    {
        $draft = Campus::factory()->unverified()->create(['name' => 'Kampus Draft', 'slug' => 'kampus-draft']);

        $this->get('/universities')->assertOk()->assertDontSee('Kampus Draft');
        $this->get('/universities/'.$draft->slug)->assertNotFound();
    }

    public function test_index_rejects_invalid_filter_values(): void
    {
        $this->get('/universities?program_type=ngawur')->assertSessionHasErrors('program_type');
    }

    public function test_listing_eager_loads_study_programs_for_card_badges(): void
    {
        $campus = Campus::factory()->create();
        StudyProgram::factory()->create(['campus_id' => $campus->id]);

        $listed = $this->get('/universities')->viewData('campuses')->first();

        $this->assertTrue($listed->relationLoaded('studyPrograms'));
    }
}

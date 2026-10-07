<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\CampusPhoto;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusDetailExtrasTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_shows_testimonials_with_tab_link(): void
    {
        $campus = Campus::factory()->create();
        Testimonial::create([
            'campus_id' => $campus->id,
            'name' => 'Aditya Nugraha',
            'major_name' => 'Ilmu Komputer',
            'current_job' => 'Software Engineer',
            'quote' => 'Kurikulumnya menantang.',
            'graduation_year' => 2021,
        ]);

        $this->get(route('campuses.show', $campus))
            ->assertOk()
            ->assertSee('Kurikulumnya menantang.')
            ->assertSee('Aditya Nugraha')
            ->assertSee('angkatan 2021')
            ->assertSee('href="#testimoni"', false);
    }

    public function test_detail_shows_gallery_with_caption(): void
    {
        $campus = Campus::factory()->create();
        CampusPhoto::create([
            'campus_id' => $campus->id,
            'path' => 'images/campuses/contoh/contoh-1.jpg',
            'caption' => 'Gedung rektorat. Foto: Contoh, CC BY-SA 4.0, Wikimedia Commons',
            'sort' => 0,
        ]);

        $this->get(route('campuses.show', $campus))
            ->assertOk()
            ->assertSee('Galeri kampus')
            ->assertSee('Gedung rektorat. Foto: Contoh', false)
            ->assertSee('loading="lazy"', false);
    }

    public function test_detail_hides_gallery_and_testimonials_when_empty(): void
    {
        $campus = Campus::factory()->create();

        $this->get(route('campuses.show', $campus))
            ->assertOk()
            ->assertDontSee('id="galeri"', false)
            ->assertDontSee('id="testimoni"', false)
            ->assertDontSee('href="#galeri"', false);
    }

    public function test_photos_are_ordered_by_sort(): void
    {
        $campus = Campus::factory()->create();
        CampusPhoto::create(['campus_id' => $campus->id, 'path' => 'b.jpg', 'sort' => 2]);
        CampusPhoto::create(['campus_id' => $campus->id, 'path' => 'a.jpg', 'sort' => 1]);

        $this->assertSame(['a.jpg', 'b.jpg'], $campus->photos->pluck('path')->all());
    }

    public function test_testimonial_initials_use_first_two_words(): void
    {
        $testimonial = new Testimonial(['name' => 'Neng Siti Aminah']);

        $this->assertSame('NS', $testimonial->initials());
    }
}

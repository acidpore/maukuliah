<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Major;
use App\Models\Scholarship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerAndScholarshipRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_career_routes_are_registered_and_bind_by_slug(): void
    {
        $career = Career::factory()->create();
        $career->majors()->attach(Major::factory()->create());

        $this->assertSame(url('/jobs'), route('careers.index'));
        $this->assertSame(url('/jobs/'.$career->slug), route('careers.show', $career));
    }

    public function test_scholarship_open_scope_excludes_closed_periods(): void
    {
        $open = Scholarship::factory()->create();
        Scholarship::factory()->closed()->create();

        $this->assertSame([$open->id], Scholarship::open()->pluck('id')->all());
        $this->assertTrue($open->isOpen());
    }

    public function test_career_search_matches_name(): void
    {
        Career::factory()->create(['name' => 'Konsultan Pajak', 'slug' => 'konsultan-pajak']);
        Career::factory()->create(['name' => 'Arsitek', 'slug' => 'arsitek']);

        $this->assertSame(['Konsultan Pajak'], Career::search('Pajak')->pluck('name')->all());
    }
}

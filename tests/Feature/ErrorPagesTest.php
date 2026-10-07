<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_page_renders_branded_404(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan');
    }

    public function test_application_form_exposes_campus_major_map(): void
    {
        $this->get(route('applications.create'))
            ->assertOk()
            ->assertSee('data-campus-majors', false);
    }
}

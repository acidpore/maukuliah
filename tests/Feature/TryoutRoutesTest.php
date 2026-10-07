<?php

namespace Tests\Feature;

use Tests\TestCase;

class TryoutRoutesTest extends TestCase
{
    public function test_index_lists_tryouts(): void
    {
        $this->get(route('tryouts.index'))
            ->assertOk()
            ->assertSee('Tryout Penalaran Umum')
            ->assertSee('Segera');
    }

    public function test_open_tryout_can_be_taken(): void
    {
        $this->get(route('tryouts.take', 'penalaran-umum'))
            ->assertOk()
            ->assertSee('data-countdown', false);
    }

    public function test_upcoming_tryout_cannot_be_taken(): void
    {
        $this->get(route('tryouts.show', 'bahasa-inggris'))->assertOk();
        $this->get(route('tryouts.take', 'bahasa-inggris'))->assertNotFound();
    }

    public function test_unknown_tryout_is_not_found(): void
    {
        $this->get(route('tryouts.show', 'tidak-ada'))->assertNotFound();
    }

    public function test_submit_scores_answers_and_counts_blank_as_wrong(): void
    {
        $this->post(route('tryouts.submit', 'matematika-dasar'), ['answers' => [0 => 2, 1 => 2]])
            ->assertOk()
            ->assertSee('2 benar dari 4 soal')
            ->assertSee('tidak dijawab');
    }

    public function test_submit_without_answers_scores_zero(): void
    {
        $this->post(route('tryouts.submit', 'matematika-dasar'))
            ->assertOk()
            ->assertSee('0 benar dari 4 soal');
    }
}

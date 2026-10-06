<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\TestResult;
use App\Models\User;
use App\Services\PotentialTestRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\StubsViews;
use Tests\TestCase;

class PotentialTestRoutesTest extends TestCase
{
    use RefreshDatabase;
    use StubsViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubViews(['tests.index', 'tests.start', 'tests.result', 'tests.history']);
    }

    /**
     * @return array<int, int|string>
     */
    private function answers(string $key): array
    {
        $registry = app(PotentialTestRegistry::class);
        $value = $registry->allowedValues($key)[0];

        return array_fill(0, $registry->questionCount($key), $value);
    }

    public function test_index_lists_all_tests(): void
    {
        $response = $this->get('/test')->assertOk()->assertViewIs('tests.index');

        $tests = $response->viewData('tests');

        $this->assertSame(['riasec', 'learning-style', 'mbti'], array_column($tests, 'key'));
        $this->assertSame(
            ['key', 'title', 'description', 'badge', 'duration_label'],
            array_keys($tests[0]),
        );
    }

    public function test_riasec_alias_redirects_to_test_page(): void
    {
        $this->get('/riasec')->assertRedirect('/test/riasec');
    }

    public function test_start_exposes_uniform_question_shape_for_every_test(): void
    {
        foreach (['riasec', 'learning-style', 'mbti'] as $key) {
            $response = $this->get("/test/{$key}")->assertOk()->assertViewIs('tests.start');

            $question = $response->viewData('questions')[0];

            $this->assertSame(['index', 'text', 'options'], array_keys($question));
            $this->assertSame($key, $response->viewData('type'));
            $this->assertNotEmpty($question['options']);
        }
    }

    public function test_likert_tests_expose_scale_labels_and_mbti_does_not(): void
    {
        $this->assertCount(5, $this->get('/test/riasec')->viewData('scaleLabels'));
        $this->assertSame([], $this->get('/test/mbti')->viewData('scaleLabels'));
    }

    public function test_unknown_test_type_is_not_found(): void
    {
        $this->get('/test/tidak-ada')->assertNotFound();
    }

    public function test_logged_in_user_submits_and_sees_result(): void
    {
        Major::factory()->create(['riasec_codes' => 'RIA']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/test/riasec', ['answers' => $this->answers('riasec')]);

        $result = TestResult::firstOrFail();
        $response->assertRedirect(route('tests.result', $result));
        $this->assertSame($user->id, $result->user_id);

        $page = $this->actingAs($user)->get(route('tests.result', $result))
            ->assertOk()
            ->assertViewIs('tests.result');

        $this->assertSame(
            ['title', 'headline', 'description'],
            array_keys($page->viewData('summary')),
        );
        $this->assertSame(['label', 'value', 'max'], array_keys($page->viewData('scores')[0]));
        $this->assertNotEmpty($page->viewData('recommendedMajors'));
    }

    public function test_every_test_type_produces_a_result_page(): void
    {
        $user = User::factory()->create();

        foreach (['riasec', 'learning-style', 'mbti'] as $key) {
            $this->actingAs($user)->post("/test/{$key}", ['answers' => $this->answers($key)]);
        }

        $this->assertSame(3, TestResult::count());

        TestResult::all()->each(fn (TestResult $result) => $this->actingAs($user)
            ->get(route('tests.result', $result))
            ->assertOk());
    }

    public function test_incomplete_answers_are_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/test/riasec', ['answers' => [0 => 3]])
            ->assertSessionHasErrors();

        $this->assertSame(0, TestResult::count());
    }

    public function test_out_of_range_answer_is_rejected(): void
    {
        $answers = $this->answers('riasec');
        $answers[0] = 99;

        $this->actingAs(User::factory()->create())
            ->post('/test/riasec', ['answers' => $answers])
            ->assertSessionHasErrors('answers.0');
    }

    public function test_guest_submission_is_kept_and_finished_after_login(): void
    {
        $this->post('/test/riasec', ['answers' => $this->answers('riasec')])
            ->assertRedirect(route('login'));

        $this->assertSame(0, TestResult::count());

        $user = User::factory()->create(['password' => 'rahasia-banget']);

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia-banget'])
            ->assertRedirect(route('tests.resume'));

        $this->get(route('tests.resume'))->assertRedirect(route('tests.result', TestResult::firstOrFail()));
        $this->assertSame($user->id, TestResult::firstOrFail()->user_id);
    }

    public function test_resume_without_pending_test_goes_to_index(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('tests.resume'))
            ->assertRedirect(route('tests.index'));
    }

    public function test_result_is_only_visible_to_owner(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->post('/test/riasec', ['answers' => $this->answers('riasec')]);
        $result = TestResult::firstOrFail();

        $this->actingAs(User::factory()->create())
            ->get(route('tests.result', $result))
            ->assertForbidden();
    }

    public function test_result_and_history_require_login(): void
    {
        $this->get('/test/history')->assertRedirect(route('login'));
        $this->get('/test/results/1')->assertRedirect(route('login'));
    }

    public function test_history_lists_only_own_results(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($me)->post('/test/mbti', ['answers' => $this->answers('mbti')]);
        $this->actingAs($other)->post('/test/riasec', ['answers' => $this->answers('riasec')]);

        $response = $this->actingAs($me)->get('/test/history')->assertOk()->assertViewIs('tests.history');

        $this->assertCount(1, $response->viewData('results'));
        $this->assertArrayHasKey('mbti', $response->viewData('testTitles'));
    }
}

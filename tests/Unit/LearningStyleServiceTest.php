<?php

namespace Tests\Unit;

use App\Models\TestResult;
use App\Models\User;
use App\Services\LearningStyleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class LearningStyleServiceTest extends TestCase
{
    use RefreshDatabase;

    private LearningStyleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new LearningStyleService;
    }

    public function test_questions_cover_every_style_five_times(): void
    {
        $counts = array_count_values(array_column($this->service->questions(), 'style'));

        $this->assertCount(20, $this->service->questions());
        $this->assertSame(['visual' => 5, 'auditori' => 5, 'kinestetik' => 5, 'baca_tulis' => 5], $counts);
    }

    public function test_score_picks_dominant_style_with_tips(): void
    {
        $result = $this->service->score($this->answersFavoring('kinestetik'));

        $this->assertSame('kinestetik', $result['dominant']);
        $this->assertSame('Kinestetik', $result['label']);
        $this->assertSame(25, $result['scores']['kinestetik']);
        $this->assertSame(5, $result['scores']['visual']);
        $this->assertNotEmpty($result['tips']);
    }

    public function test_score_rejects_incomplete_or_out_of_range_answers(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $answers = $this->answersFavoring('visual');
        $answers[0] = 9;

        $this->service->score($answers);
    }

    public function test_submit_stores_result_for_user(): void
    {
        $user = User::factory()->create();

        $stored = $this->service->submit($user, $this->answersFavoring('auditori'));

        $this->assertSame(TestResult::TYPE_LEARNING_STYLE, $stored->test_type);
        $this->assertSame('auditori', $stored->result['dominant']);
        $this->assertSame(1, $user->testResults()->count());
    }

    /**
     * @return array<int, int>
     */
    private function answersFavoring(string $style): array
    {
        return collect($this->service->questions())
            ->map(fn (array $question) => $question['style'] === $style ? 5 : 1)
            ->all();
    }
}

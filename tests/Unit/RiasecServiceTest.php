<?php

namespace Tests\Unit;

use App\Models\Major;
use App\Models\TestResult;
use App\Models\User;
use App\Services\RiasecService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class RiasecServiceTest extends TestCase
{
    use RefreshDatabase;

    private RiasecService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RiasecService;
    }

    public function test_questions_cover_every_type_four_times(): void
    {
        $counts = array_count_values(array_column($this->service->questions(), 'type'));

        $this->assertCount(24, $this->service->questions());
        $this->assertSame(['R' => 4, 'I' => 4, 'A' => 4, 'S' => 4, 'E' => 4, 'C' => 4], $counts);
    }

    public function test_score_sums_answers_per_type_and_builds_top_code(): void
    {
        $scores = $this->service->score($this->answersFavoring(['I' => 5, 'R' => 4, 'C' => 3]));

        $this->assertSame(20, $scores['scores']['I']);
        $this->assertSame(16, $scores['scores']['R']);
        $this->assertSame('IRC', $scores['code']);
    }

    public function test_score_rejects_incomplete_or_out_of_range_answers(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $answers = $this->answersFavoring([]);
        $answers[0] = 9;

        $this->service->score($answers);
    }

    public function test_recommend_ranks_matching_major_first(): void
    {
        $match = Major::factory()->create(['riasec_codes' => 'IRC']);
        Major::factory()->create(['riasec_codes' => 'ASE']);

        $scores = $this->service->score($this->answersFavoring(['I' => 5, 'R' => 4, 'C' => 3]))['scores'];

        $this->assertTrue($this->service->recommend($scores)->first()['major']->is($match));
    }

    public function test_submit_stores_result_for_user(): void
    {
        Major::factory()->create(['riasec_codes' => 'IRC']);
        $user = User::factory()->create();

        $result = $this->service->submit($user, $this->answersFavoring(['I' => 5]));

        $this->assertInstanceOf(TestResult::class, $result);
        $this->assertSame(TestResult::TYPE_RIASEC, $result->test_type);
        $this->assertSame('I', substr($result->result['code'], 0, 1));
        $this->assertDatabaseCount('test_results', 1);
    }

    /**
     * @param  array<string, int>  $valueByType  Nilai Likert per tipe, sisanya 1.
     * @return array<int, int>
     */
    private function answersFavoring(array $valueByType): array
    {
        $answers = [];

        foreach ($this->service->questions() as $index => $question) {
            $answers[$index] = $valueByType[$question['type']] ?? 1;
        }

        return $answers;
    }
}

<?php

namespace Tests\Unit;

use App\Models\Major;
use App\Models\TestResult;
use App\Models\User;
use App\Services\MbtiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class MbtiServiceTest extends TestCase
{
    use RefreshDatabase;

    private MbtiService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MbtiService;
    }

    public function test_questions_cover_every_dimension_ten_times(): void
    {
        $counts = array_count_values(array_column($this->service->questions(), 'dimension'));

        $this->assertCount(40, $this->service->questions());
        $this->assertSame(['EI' => 10, 'SN' => 10, 'TF' => 10, 'JP' => 10], $counts);
    }

    public function test_every_type_has_a_description_and_categories(): void
    {
        $types = config('mbti.types');

        $this->assertCount(16, $types);

        foreach ($types as $type) {
            $this->assertNotEmpty($type['summary']);
            $this->assertNotEmpty($type['categories']);
        }
    }

    public function test_score_builds_four_letter_code_from_choices(): void
    {
        $result = $this->service->score($this->answersFor('INTJ'));

        $this->assertSame('INTJ', $result['code']);
        $this->assertSame(10, $result['scores']['I']);
        $this->assertSame(0, $result['scores']['E']);
        $this->assertSame('Arsitek', $result['name']);
        $this->assertContains('Komputer & Informatika', $result['categories']);
    }

    public function test_tie_falls_back_to_configured_letter(): void
    {
        $answers = $this->answersFor('ESTJ');

        foreach ($this->questions('EI', 5) as $index) {
            $answers[$index] = 'b';
        }

        $this->assertSame('ISTJ', $this->service->score($answers)['code']);
    }

    public function test_score_rejects_incomplete_or_invalid_answers(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $answers = $this->answersFor('INTJ');
        $answers[0] = 'x';

        $this->service->score($answers);
    }

    public function test_recommend_returns_majors_in_suggested_categories(): void
    {
        $match = Major::factory()->create(['category' => 'Komputer & Informatika']);
        Major::factory()->create(['category' => 'Pertanian']);

        $recommended = $this->service->recommend(['Komputer & Informatika']);

        $this->assertCount(1, $recommended);
        $this->assertTrue($recommended->first()->is($match));
    }

    public function test_submit_stores_code_and_recommended_major_ids(): void
    {
        $user = User::factory()->create();
        $major = Major::factory()->create(['category' => 'Komputer & Informatika']);

        $stored = $this->service->submit($user, $this->answersFor('INTJ'));

        $this->assertSame(TestResult::TYPE_MBTI, $stored->test_type);
        $this->assertSame('INTJ', $stored->result['code']);
        $this->assertContains($major->id, $stored->result['recommendations']);
    }

    /**
     * @return array<int, string>
     */
    private function answersFor(string $code): array
    {
        $letters = str_split($code);

        return collect($this->service->questions())
            ->map(fn (array $question) => in_array($question['dimension'][0], $letters, true) ? 'a' : 'b')
            ->all();
    }

    /**
     * @return array<int, int>
     */
    private function questions(string $dimension, int $limit): array
    {
        return collect($this->service->questions())
            ->filter(fn (array $question) => $question['dimension'] === $dimension)
            ->keys()
            ->take($limit)
            ->all();
    }
}

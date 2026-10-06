<?php

namespace App\Services;

use App\Models\Major;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class MbtiService
{
    private const CHOICE_FIRST = 'a';

    private const CHOICE_SECOND = 'b';

    /**
     * @return array<int, array{dimension: string, text: string, a: string, b: string}>
     */
    public function questions(): array
    {
        return config('mbti.questions');
    }

    /**
     * @param  array<int, string>  $answers  Pilihan 'a' atau 'b', kunci = indeks soal.
     * @return array{scores: array<string, int>, code: string, name: string, summary: string, categories: array<int, string>}
     */
    public function score(array $answers): array
    {
        $this->assertValid($answers);

        $scores = $this->emptyScores();

        foreach ($this->questions() as $index => $question) {
            $scores[$this->chosenLetter($question['dimension'], $answers[$index])]++;
        }

        $code = $this->buildCode($scores);

        return ['scores' => $scores, 'code' => $code] + config("mbti.types.{$code}");
    }

    /**
     * @param  array<int, string>  $categories
     */
    public function recommend(array $categories): Collection
    {
        return Major::query()
            ->whereIn('category', $categories)
            ->orderBy('name')
            ->limit(config('mbti.recommendation_limit'))
            ->get();
    }

    /**
     * @param  array<int, string>  $answers
     */
    public function submit(User $user, array $answers): TestResult
    {
        $result = $this->score($answers);

        $result['recommendations'] = $this->recommend($result['categories'])
            ->pluck('id')
            ->all();

        return $user->testResults()->create([
            'test_type' => TestResult::TYPE_MBTI,
            'result' => $result,
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function emptyScores(): array
    {
        $letters = array_merge(...array_map('str_split', config('mbti.dimensions')));

        return array_fill_keys($letters, 0);
    }

    private function chosenLetter(string $dimension, string $choice): string
    {
        return $dimension[$choice === self::CHOICE_FIRST ? 0 : 1];
    }

    /**
     * @param  array<string, int>  $scores
     */
    private function buildCode(array $scores): string
    {
        $code = '';

        foreach (config('mbti.dimensions') as $dimension) {
            [$first, $second] = str_split($dimension);

            $code .= match (true) {
                $scores[$first] > $scores[$second] => $first,
                $scores[$second] > $scores[$first] => $second,
                default => config("mbti.tie_breakers.{$dimension}"),
            };
        }

        return $code;
    }

    /**
     * @param  array<int, string>  $answers
     */
    private function assertValid(array $answers): void
    {
        $allowed = [self::CHOICE_FIRST, self::CHOICE_SECOND];

        foreach (array_keys($this->questions()) as $index) {
            if (! in_array($answers[$index] ?? null, $allowed, true)) {
                throw new InvalidArgumentException("Jawaban soal {$index} tidak valid.");
            }
        }
    }
}

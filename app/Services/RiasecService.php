<?php

namespace App\Services;

use App\Models\Major;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class RiasecService
{
    /**
     * Bobot posisi huruf pada kode jurusan: huruf pertama paling berpengaruh.
     */
    private const POSITION_WEIGHTS = [3, 2, 1];

    /**
     * @return array<int, array{type: string, text: string}>
     */
    public function questions(): array
    {
        return config('riasec.questions');
    }

    /**
     * @param  array<int, int>  $answers  Jawaban Likert, kunci = indeks soal.
     * @return array{scores: array<string, int>, code: string}
     */
    public function score(array $answers): array
    {
        $this->assertValid($answers);

        $scores = array_fill_keys(array_keys(config('riasec.types')), 0);

        foreach ($this->questions() as $index => $question) {
            $scores[$question['type']] += (int) $answers[$index];
        }

        return [
            'scores' => $scores,
            'code' => $this->topCode($scores),
        ];
    }

    /**
     * @param  array<string, int>  $scores
     */
    public function recommend(array $scores): Collection
    {
        return Major::query()
            ->whereNotNull('riasec_codes')
            ->get()
            ->map(fn (Major $major) => [
                'major' => $major,
                'match' => $this->matchScore($major->riasec_codes, $scores),
            ])
            ->sortByDesc('match')
            ->take(config('riasec.recommendation_limit'))
            ->values();
    }

    /**
     * @param  array<int, int>  $answers
     */
    public function submit(User $user, array $answers): TestResult
    {
        $result = $this->score($answers);

        $result['recommendations'] = $this->recommend($result['scores'])
            ->map(fn (array $item) => [
                'major_id' => $item['major']->id,
                'match' => $item['match'],
            ])
            ->all();

        return $user->testResults()->create([
            'test_type' => TestResult::TYPE_RIASEC,
            'result' => $result,
        ]);
    }

    /**
     * @param  array<string, int>  $scores
     */
    private function topCode(array $scores): string
    {
        $letters = array_keys($scores);

        // Urutan huruf asli menjadi pemutus seri agar hasil deterministik.
        usort($letters, fn (string $a, string $b) => [$scores[$b], array_search($a, array_keys($scores))]
            <=> [$scores[$a], array_search($b, array_keys($scores))]);

        return implode('', array_slice($letters, 0, config('riasec.code_length')));
    }

    /**
     * @param  array<string, int>  $scores
     */
    private function matchScore(string $codes, array $scores): int
    {
        $total = 0;

        foreach (str_split($codes) as $position => $letter) {
            $total += (self::POSITION_WEIGHTS[$position] ?? 0) * ($scores[$letter] ?? 0);
        }

        return $total;
    }

    /**
     * @param  array<int, int>  $answers
     */
    private function assertValid(array $answers): void
    {
        $min = config('riasec.scale.min');
        $max = config('riasec.scale.max');

        foreach (array_keys($this->questions()) as $index) {
            $value = $answers[$index] ?? null;

            if (! is_numeric($value) || $value < $min || $value > $max) {
                throw new InvalidArgumentException("Jawaban soal {$index} tidak valid.");
            }
        }
    }
}

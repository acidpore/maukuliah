<?php

namespace App\Services;

use App\Models\Major;
use App\Models\TestResult;
use Illuminate\Support\Collection;

/**
 * Mengubah hasil mentah tiap tes menjadi bentuk seragam untuk halaman hasil.
 */
class TestResultPresenter
{
    private const MBTI_SCORE_UNIT = 1;

    public function __construct(private readonly PotentialTestRegistry $registry) {}

    /**
     * @return array{summary: array{title: string, headline: string, description: string}, scores: array<int, array{label: string, value: int, max: int}>, majors: Collection<int, Major>}
     */
    public function present(TestResult $testResult): array
    {
        $result = $testResult->result;

        [$headline, $description, $scores] = match ($testResult->test_type) {
            TestResult::TYPE_RIASEC => $this->riasec($result),
            TestResult::TYPE_LEARNING_STYLE => $this->learningStyle($result),
            TestResult::TYPE_MBTI => $this->mbti($result),
        };

        return [
            'summary' => [
                'title' => $this->registry->titleForType($testResult->test_type),
                'headline' => $headline,
                'description' => $description,
            ],
            'scores' => $scores,
            'majors' => $this->majors($testResult),
        ];
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array{0: string, 1: string, 2: array<int, array{label: string, value: int, max: int}>}
     */
    private function riasec(array $result): array
    {
        $types = config('riasec.types');
        $max = $this->groupMax('riasec', count($types), config('riasec.scale.max'));
        $labels = array_map(fn (string $letter) => $types[$letter], str_split($result['code']));

        return [
            'Kode RIASEC: '.$result['code'],
            'Kecenderungan utamamu: '.implode(', ', $labels).'.',
            $this->scoreRows($result['scores'], $types, $max),
        ];
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array{0: string, 1: string, 2: array<int, array{label: string, value: int, max: int}>}
     */
    private function learningStyle(array $result): array
    {
        $styles = config('learning_style.styles');
        $labels = array_map(fn (array $style) => $style['label'], $styles);
        $max = $this->groupMax('learning-style', count($styles), config('learning_style.scale.max'));

        return [
            $result['label'],
            $result['tips'],
            $this->scoreRows($result['scores'], $labels, $max),
        ];
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array{0: string, 1: string, 2: array<int, array{label: string, value: int, max: int}>}
     */
    private function mbti(array $result): array
    {
        $max = $this->groupMax('mbti', count(config('mbti.dimensions')), self::MBTI_SCORE_UNIT);
        $letters = array_combine(array_keys($result['scores']), array_keys($result['scores']));

        return [
            $result['code'].' - '.$result['name'],
            $result['summary'],
            $this->scoreRows($result['scores'], $letters, $max),
        ];
    }

    /**
     * @param  array<string, int>  $scores
     * @param  array<string, string>  $labels
     * @return array<int, array{label: string, value: int, max: int}>
     */
    private function scoreRows(array $scores, array $labels, int $max): array
    {
        return collect($scores)
            ->map(fn (int $value, string $key) => [
                'label' => $labels[$key] ?? $key,
                'value' => $value,
                'max' => $max,
            ])
            ->values()
            ->all();
    }

    /**
     * Skor tertinggi satu kelompok: jumlah soal per kelompok dikali nilai maksimal.
     */
    private function groupMax(string $testKey, int $groups, int $maxValue): int
    {
        return intdiv($this->registry->questionCount($testKey), $groups) * $maxValue;
    }

    /**
     * @return Collection<int, Major>
     */
    private function majors(TestResult $testResult): Collection
    {
        $ids = collect($testResult->result['recommendations'] ?? [])
            ->map(fn ($item) => is_array($item) ? $item['major_id'] : $item)
            ->values();

        $majors = Major::whereIn('id', $ids)->get()->keyBy('id');

        return $ids->map(fn (int $id) => $majors->get($id))->filter()->values();
    }
}

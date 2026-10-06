<?php

namespace App\Services;

use App\Models\TestResult;
use App\Models\User;
use InvalidArgumentException;

/**
 * Pintu tunggal ke semua tes potensi agar controller tidak bercabang per tipe.
 */
class PotentialTestRegistry
{
    private const INPUT_LIKERT = 'likert';

    public function __construct(
        private readonly RiasecService $riasec,
        private readonly LearningStyleService $learningStyle,
        private readonly MbtiService $mbti,
    ) {}

    /**
     * @return array<int, array{key: string, title: string, description: string, badge: string, duration_label: string}>
     */
    public function all(): array
    {
        return array_map(
            fn (string $key) => $this->descriptor($key),
            array_keys(config('potential_tests.tests')),
        );
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, config('potential_tests.tests'));
    }

    /**
     * @return array{key: string, title: string, description: string, badge: string, duration_label: string}
     */
    public function descriptor(string $key): array
    {
        $test = $this->definition($key);

        return [
            'key' => $key,
            'title' => $test['title'],
            'description' => $test['description'],
            'badge' => $test['badge'],
            'duration_label' => $test['duration_label'],
        ];
    }

    /**
     * Bentuk seragam untuk semua tes: tiap soal punya daftar opsi bernilai.
     *
     * @return array<int, array{index: int, text: string, options: array<int, array{value: int|string, label: string}>}>
     */
    public function questions(string $key): array
    {
        $likertOptions = $this->likertOptions($key);

        return collect($this->service($key)->questions())
            ->map(fn (array $question, int $index) => [
                'index' => $index,
                'text' => $question['text'],
                'options' => $this->isLikert($key)
                    ? $likertOptions
                    : [
                        ['value' => 'a', 'label' => $question['a']],
                        ['value' => 'b', 'label' => $question['b']],
                    ],
            ])
            ->all();
    }

    /**
     * Label skala untuk tes Likert, kosong untuk tes pilihan A atau B.
     *
     * @return array<int, string>
     */
    public function scaleLabels(string $key): array
    {
        return $this->isLikert($key) ? $this->likertLabels($key) : [];
    }

    /**
     * @return array<int, int|string>
     */
    public function allowedValues(string $key): array
    {
        return $this->isLikert($key) ? array_keys($this->likertLabels($key)) : ['a', 'b'];
    }

    public function questionCount(string $key): int
    {
        return count($this->service($key)->questions());
    }

    /**
     * @param  array<int, int|string>  $answers
     */
    public function submit(string $key, User $user, array $answers): TestResult
    {
        return $this->service($key)->submit($user, $answers);
    }

    public function titleForType(string $type): string
    {
        $key = $this->keyForType($type);

        return $key === null ? $type : config("potential_tests.tests.{$key}.title");
    }

    /**
     * @return array<string, string> Judul tes dengan kunci tipe hasil (test_type).
     */
    public function titlesByType(): array
    {
        return collect(config('potential_tests.tests'))
            ->mapWithKeys(fn (array $test) => [$test['type'] => $test['title']])
            ->all();
    }

    public function keyForType(string $type): ?string
    {
        foreach (config('potential_tests.tests') as $key => $test) {
            if ($test['type'] === $type) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function definition(string $key): array
    {
        return config("potential_tests.tests.{$key}")
            ?? throw new InvalidArgumentException("Tes {$key} tidak dikenal.");
    }

    private function isLikert(string $key): bool
    {
        return $this->definition($key)['input'] === self::INPUT_LIKERT;
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    private function likertOptions(string $key): array
    {
        if (! $this->isLikert($key)) {
            return [];
        }

        return collect($this->likertLabels($key))
            ->map(fn (string $label, int $value) => ['value' => $value, 'label' => $label])
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function likertLabels(string $key): array
    {
        $scale = config(str_replace('-', '_', $key).'.scale');

        return array_filter(
            config('potential_tests.likert_labels'),
            fn (string $label, int $value) => $value >= $scale['min'] && $value <= $scale['max'],
            ARRAY_FILTER_USE_BOTH,
        );
    }

    private function service(string $key): RiasecService|LearningStyleService|MbtiService
    {
        $this->definition($key);

        return match ($key) {
            'riasec' => $this->riasec,
            'learning-style' => $this->learningStyle,
            'mbti' => $this->mbti,
        };
    }
}
